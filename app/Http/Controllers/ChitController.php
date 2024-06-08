<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChitRequest;
use App\Http\Requests\UpdateChitRequest;
use App\Models\Chit;
use App\Models\Department;
use App\Models\FeeType;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ChitController extends Controller
{
    public function issued_chits(Patient $patient)
    {
        return view('chit.issued_chits', compact('patient'));
    }

    public function issued_invoices(Patient $patient)
    {
        return view('invoices.issued_invoices', compact('patient'));
    }

    public function issue_new_chit(Patient $patient)
    {
        return view('chit.issue_new_chit', compact('patient'));
    }


    public function issue_new_chit_store(Request $request, Patient $patient)
    {
        $request->validate([
            'ipd_opd' => 'required',
            'government_department_id' => 'required_with:government_card_no,designation',
            'government_card_no' => 'required_with:government_department_id',
            'designation' => 'required_with:government_department_id',
            'department_id' => 'required',
        ]);


//        dd($request->all());

        // login user id capture
        $request->merge(['user_id' => auth()->user()->id]);
        $count_chit_of_today = Chit::where('department_id', $request->department_id)->where('issued_date', '>=', Carbon::today())->count();
        $count_chit_of_today_limit = Department::where('id', $request->department_id)->first()->daily_patient_limit;
        $count_chit_of_today++;
        $chit = null;
        // 46 >= 51
        if ($count_chit_of_today_limit <= $count_chit_of_today) {
            return to_route('patient.create')->with('error', 'OPD today\'s limit has been reached to maximum limit of ' . $count_chit_of_today_limit . ' as assigned by OPD.');
        }

        DB::beginTransaction();

        try {
            $amount = 0;
            $fee_type_id = null;
            $ipd_opd = $request->ipd_opd;
            $amount_hif = 0;
            $govt_amount = 0;
            if ($request->input('government_department_id')) {
                $amount = 0.00;
                $amount_hif = 0.00;
                $govt_amount = 0.00;
                if ($request->department_id == 7) {
                    $fee_type_id = FeeType::find(108)->id;
                    $amount = FeeType::find(108)->amount;
                    $amount_hif = FeeType::find(108)->hif;
                    $govt_amount = $amount - $amount_hif;
                }
                else {
                    if ($request->department_id == 1) {
                        $fee_type_id = FeeType::find(1)->id;
                    }
                    else {
                        if ($request->department_id == 16) {
                            $fee_type_id = FeeType::find(1)->id;
                        }
                        else {
                            $fee_type_id = FeeType::find(107)->id;
                        }
                    }
                }
            }
            else {
                if ($request->department_id == 7) {
                    $amount = FeeType::find(108)->amount;
                    $amount_hif = FeeType::find(108)->hif;
                    $fee_type_id = 108;
                    // as all amount goes to government
                    $govt_amount = $amount;
                }
                else {
                    if ($request->department_id == 1) {
                        // For emergency
                        $amount = FeeType::find(1)->amount;
                        $amount_hif = FeeType::find(1)->hif;
                        $fee_type_id = 1;
                        $govt_amount = $amount - $amount_hif;
                    }
                    else {
                        if ($request->department_id == 16) {
                            // For Cardiology
                            $amount = FeeType::find(19)->amount;
                            $amount_hif = FeeType::find(19)->hif;
                            $fee_type_id = 1;
                            $govt_amount = $amount - $amount_hif;
                        }
                        else {
                            $fee_type_id = 107;
                            $amount = FeeType::find(107)->amount;
                            $amount_hif = FeeType::find(107)->hif;
                            $govt_amount = $amount - $amount_hif;
                        }
                    }
                }
            }


            if ($request->has('ipd_opd')) {
                $ipd_opd = 0;
            }
            else {
                $ipd_opd = 1;
            }


            // this is for opd and ipd
            $chit = Chit::create([
                'user_id' => $request->user_id,
                'department_id' => $request->department_id,
                'patient_id' => $patient->id,
                'government_non_gov' => $request->government_non_gov,
                'government_department_id' => $request->government_department_id,
                'government_card_no' => $request->government_card_no,
                'designation' => $request->designation,
                'fee_type_id' => $fee_type_id,
                'issued_date' => now(),
                'amount' => $amount,
                'amount_hif' => $amount_hif,
                'govt_amount' => $govt_amount,
                'ipd_opd' => $ipd_opd,
                'payment_status' => 1,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

        return to_route('chit.print', [$patient->id, $chit->id]);
    }

    /**
     * Display a listing of the resource.
     */
    public function today()
    {
        $user = \Auth::user();
        $issued_chits = null;
        if ($user->hasRole('Front Desk/Receptionist')) {
            $issued_chits = QueryBuilder::for(Chit::class)
                ->allowedFilters(['patient_id', 'fee_type_id', 'government_department_id', 'issued_date', 'ipd_opd', 'government_card_no', AllowedFilter::exact('government_non_gov'), AllowedFilter::exact('id'), AllowedFilter::exact('department_id')],)
                ->where('user_id', $user->id)->whereDate('issued_date', Carbon::today())
//                ->where('user_id', $user->id)->where('ipd_opd', 1)->whereDate('issued_date', Carbon::today())
//                ->orderByDesc('created_at') // Corrected 'DSEC' to 'DESC'
                ->paginate(500);
        }
        elseif ($user->hasRole(['Administrator'])) {
            $issued_chits = QueryBuilder::for(Chit::class)
                ->allowedFilters(['patient_id', 'fee_type_id', 'government_department_id', 'issued_date', 'ipd_opd', 'government_card_no', AllowedFilter::exact('government_non_gov'), AllowedFilter::exact('id'), AllowedFilter::exact('department_id')],)
                ->whereDate('issued_date', Carbon::today())
//                ->orderByDesc('created_at') // Corrected 'DSEC' to 'DESC'
                ->paginate(1000);
        }

        return view('chit.today', compact('issued_chits'));
    }


    public function issued(Request $request)
    {
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');

        $date_start_at = $start_date . ' 00:00:00';
        $date_end_at = $end_date . ' 23:59:59';


        $user = \Auth::user();
        $issued_chits = null;
        if ($user->hasRole(['Administrator'])) {
            $issued_chits = QueryBuilder::for(Chit::class)->with('user', 'patient', 'department')
                ->allowedFilters([
                    AllowedFilter::exact('department_id'),
                    AllowedFilter::exact('patient_id'),
                    AllowedFilter::exact('fee_type_id'),
                    AllowedFilter::exact('government_department_id'),
                    AllowedFilter::exact('government_non_gov'),
                    AllowedFilter::exact('government_card_no'),
                    AllowedFilter::exact('patient.sex'),
                    AllowedFilter::exact('user_id'),
                    'government_department_id',
                    'issued_date'
                ])
                ->whereBetween('created_at', [$date_start_at, $date_end_at])
                ->orderBy('created_at') // Corrected 'DSEC' to 'DESC'
                ->paginate(100000)->withQueryString();
        }
        return view('chit.issued', compact('issued_chits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChitRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Chit $chit)
    {
        //
    }


    public function print(Patient $patient, Chit $chit)
    {
        $date_of_day = Carbon::parse($chit->issued_date)->format('Y-m-d');

        $result = DB::table('chits')
            ->where('department_id', $chit->department_id)
            ->whereDate('issued_date', $date_of_day)
            ->orderByDesc('issued_date')
            ->select('*', DB::raw('ROW_NUMBER() OVER (ORDER BY created_at) AS count_no'))
            ->get();

        $chitNumber = $result->where('id', $chit->id)->first()->count_no;
        return view('chit.print', compact('chit', 'patient', 'chitNumber'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chit $chit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChitRequest $request, Chit $chit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chit $chit)
    {
        //
    }
}

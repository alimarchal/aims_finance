<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Admission;
use App\Models\Chit;
use App\Models\Department;
use App\Models\FeeCategory;
use App\Models\FeeType;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PatientTest;
use App\Models\PatientTestCart;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;


class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = QueryBuilder::for(Patient::class)
            ->allowedFilters(['first_name', 'last_name', 'father_husband_name', 'sex', 'cnic', 'mobile', 'government_non_gov', AllowedFilter::exact('id')],)
            ->orderByDesc('created_at') // Corrected 'DSEC' to 'DESC'
            ->paginate(10);
        return view('patient.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patient.create');
    }


    public function createOPD()
    {
        return view('patient.create-opd');
    }

    public function createIPD()
    {
        return view('patient.create-ipd');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        // login user id capture
        $request->merge(['user_id' => auth()->user()->id]);
        $patient = null;
        DB::beginTransaction();
        try {

            $request->merge(['sex' => $this->getAutoGender($request->input('title'))])->all();
            $age = $request->age;
            $yearsMonths = $request->years_months;
            $dateOfBirth = $request->dob; // Get the provided date of birth from the request

            // Check if the user has already provided a date of birth
            if (!$dateOfBirth) {
                if ($yearsMonths === 'Year(s)') {
                    $dateOfBirth = now()->subYears($age)->format('Y-m-d');
                } elseif ($yearsMonths === 'Month(s)') {
                    $dateOfBirth = now()->subMonths($age)->format('Y-m-d');
                } elseif ($yearsMonths === 'Day(s)') {
                    $dateOfBirth = now()->subDay($age)->format('Y-m-d');
                } else {
                    // Handle an invalid selection or provide a default value
                    $dateOfBirth = null;
                }
            }
            // Merge the calculated date of birth into the request data
            $request->merge(['dob' => $dateOfBirth])->all();
            $patient = Patient::create($request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        if (!empty($patient)) {
            return to_route('patient.actions', [$patient->id]);
        } else {
            return to_route('patient.index')->with('message', 'There is an error occurred for creating patient');
        }

    }


    public function storeOPD(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'department_id' => 'required',
            'mobile' => 'required|regex:/^03\d{2}-\d{7}$/',
            'age' => 'required|integer|min:0',
            'years_months' => 'required_if:age,!=,null|in:Year(s),Month(s),Day(s)',
            'government_non_gov' => 'required',
            'government_department_id' => 'required_with:government_card_no,designation',
            'government_card_no' => 'required_with:government_department_id',
            'designation' => 'required_with:government_department_id',
        ]);
        // login user id capture
        $request->merge(['user_id' => auth()->user()->id]);
        $patient = null;
        $chit = null;
        $fee_type_id = null;
        // 1 for opd 0 for ipd
        $ipd_opd = null;

        $count_chit_of_today = Chit::where('department_id', $request->department_id)->where('issued_date', '>=', Carbon::today())->count();
        $count_chit_of_today_limit = Department::where('id', $request->department_id)->first()->daily_patient_limit;
        $count_chit_of_today++;

        if ($count_chit_of_today_limit <= $count_chit_of_today) {
            return to_route('patient.create-opd')->with('error', 'Today\'s limit has been reached to ' . $count_chit_of_today_limit);
        }

        DB::beginTransaction();

        try {

            $request->merge(['sex' => $this->getAutoGender($request->input('title'))])->all();

            $age = $request->age;
            $yearsMonths = $request->years_months;
            $dateOfBirth = $request->dob; // Get the provided date of birth from the request

            // Check if the user has already provided a date of birth
            if (!$dateOfBirth) {
                if ($yearsMonths === 'Year(s)') {
                    $dateOfBirth = now()->subYears($age)->format('Y-m-d');
                } elseif ($yearsMonths === 'Day(s)') {
                    $dateOfBirth = now()->subDay($age)->format('Y-m-d');
                } elseif ($yearsMonths === 'Month(s)') {
                    $dateOfBirth = now()->subMonths($age)->format('Y-m-d');
                } else {
                    // Handle an invalid selection or provide a default value
                    $dateOfBirth = null;
                }
            }
            // Merge the calculated date of birth into the request data
            $request->merge(['dob' => $dateOfBirth])->all();

            $patient = Patient::create($request->all());

            $amount = null;
            if ($request->input('government_department_id')) {
                $amount = 0.00;
                if ($request->department_id == 7) {
                    $fee_type_id = 108;
                } else if ($request->department_id == 1) {
                    // For emergency
                    $fee_type_id = 1;
                } else if ($request->department_id == 16) {
                    // For Cardiology
                    $fee_type_id = 1;
                } else {
                    $fee_type_id = 107;
                }
            } else {
                if ($request->department_id == 7) {
                    $amount = FeeType::find(108)->amount;
                    $fee_type_id = 108;
                } else if ($request->department_id == 1) {
                    // For emergency
                    $amount = FeeType::find(1)->amount;
                    $fee_type_id = 1;
                } else if ($request->department_id == 16) {
                    // For Cardiology
                    $amount = FeeType::find(19)->amount;
                    $fee_type_id = 1;
                } else {
                    $fee_type_id = 107;
                    $amount = FeeType::find(107)->amount;
                }
            }

            if ($request->has('ipd_opd')) {
                $ipd_opd = 0;
            } else {
                $ipd_opd = 1;
            }

            // this is for opd
            $chit = Chit::create([
                'user_id' => auth()->user()->id,
                'department_id' => $request->department_id,
                'patient_id' => $patient->id,
                'government_non_gov' => $patient->government_non_gov,
                'government_department_id' => $patient->government_department_id,
                'government_card_no' => $patient->government_card_no,
                'designation' => $patient->designation,
                'fee_type_id' => $fee_type_id,
                'issued_date' => now(),
                'amount' => $amount,
                'ipd_opd' => $ipd_opd,
                'payment_status' => 1,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

        if (!empty($chit) && !empty($patient)) {
            return to_route('chit.print', [$patient->id, $chit->id]);
        } else {
            return to_route('patient.index')->with('message', 'There is an error occurred for creating patient and chit');
        }

    }

    public function storeIPD(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'department_id' => 'required',
            'mobile' => 'required|regex:/^03\d{2}-\d{7}$/',

            'age' => 'required|integer|min:0',
            'years_months' => 'required_if:age,!=,null|in:Year(s),Month(s),Day(s)',


            'government_non_gov' => 'required',
            'government_department_id' => 'required_with:government_card_no,designation',
            'government_card_no' => 'required_with:government_department_id',
            'designation' => 'required_with:government_department_id',
        ]);
        // login user id capture
        $request->merge(['user_id' => auth()->user()->id]);
        $patient = null;
        $chit = null;
        $fee_type_id = null;
        // 1 for opd 0 for ipd
        $ipd_opd = null;

        $count_chit_of_today = Chit::where('department_id', $request->department_id)->where('issued_date', '>=', Carbon::today())->count();
        $count_chit_of_today_limit = Department::where('id', $request->department_id)->first()->daily_patient_limit;
        $count_chit_of_today++;

        if ($count_chit_of_today_limit <= $count_chit_of_today) {
            return to_route('patient.create-ipd')->with('error', 'Today\'s limit has been reached to ' . $count_chit_of_today_limit);
        }

        DB::beginTransaction();

        try {

            $request->merge(['sex' => $this->getAutoGender($request->input('title'))])->all();

            $age = $request->age;
            $yearsMonths = $request->years_months;
            $dateOfBirth = $request->dob; // Get the provided date of birth from the request

            // Check if the user has already provided a date of birth
            if (!$dateOfBirth) {
                if ($yearsMonths === 'Year(s)') {
                    $dateOfBirth = now()->subYears($age)->format('Y-m-d');
                } elseif ($yearsMonths === 'Month(s)') {
                    $dateOfBirth = now()->subMonths($age)->format('Y-m-d');
                } elseif ($yearsMonths === 'Day(s)') {
                    $dateOfBirth = now()->subDay($age)->format('Y-m-d');
                } else {
                    // Handle an invalid selection or provide a default value
                    $dateOfBirth = null;
                }
            }
            // Merge the calculated date of birth into the request data
            $request->merge(['dob' => $dateOfBirth])->all();

            $patient = Patient::create($request->all());

            $amount = null;
            if ($request->input('government_department_id')) {
                $amount = 0.00;
            } else {
                if ($request->department_id == 7) {
                    $amount = FeeType::find(108)->amount;
                    $fee_type_id = 108;
                } else if ($request->department_id == 1) {
                    // For emergency
                    $amount = FeeType::find(1)->amount;
                    $fee_type_id = 1;
                } else if ($request->department_id == 16) {
                    // For Cardiology
                    $amount = FeeType::find(19)->amount;
                    $fee_type_id = 1;
                } else {
                    $fee_type_id = 107;
                    $amount = FeeType::find(107)->amount;
                }
            }

            if ($request->has('ipd_opd')) {
                $ipd_opd = 0;
            } else {
                $ipd_opd = 1;
            }
            // this is for opd
            $chit = Chit::create([
                'user_id' => auth()->user()->id,
                'department_id' => $request->department_id,
                'patient_id' => $patient->id,
                'government_non_gov' => $patient->government_non_gov,
                'government_department_id' => $patient->government_department_id,
                'government_card_no' => $patient->government_card_no,
                'designation' => $patient->designation,
                'fee_type_id' => $fee_type_id,
                'issued_date' => now(),
                'amount' => $amount,
                'ipd_opd' => $ipd_opd,
                'payment_status' => 1,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

        if (!empty($chit) && !empty($patient)) {
            return to_route('chit.print', [$patient->id, $chit->id]);
        } else {
            return to_route('patient.index')->with('message', 'There is an error occurred for creating patient and chit');
        }

    }


    public function proceed(Patient $patient)
    {
        return view('patient.proceed', compact('patient'));
    }

    public function add_to_cart(\Illuminate\Http\Request $request, Patient $patient)
    {
        $add_to_cart = PatientTestCart::create([
            'patient_id' => $request->patient_id,
            'fee_type_id' => $request->fee_type_id,
        ]);
        return to_route('patient.proceed', $patient->id);
    }

    public function proceed_cart_destroy(\Illuminate\Http\Request $request, PatientTestCart $patientTestCart)
    {
        $patient_id = $patientTestCart->patient_id;
        $patientTestCart->delete();
        return to_route('patient.proceed', $patient_id)->with('message', 'Lab test deleted successfully!');
    }


    public function proceed_to_invoice(\Illuminate\Http\Request $request, Patient $patient)
    {


        $request->validate([
            'terms' => 'required'
        ]);


        $patient_tests_in_carts = PatientTestCart::where('patient_id', $patient->id)->get();
        if ($patient_tests_in_carts->isEmpty()) {
            return to_route('patient.proceed', $patient->id)->with('error', 'You must select and then press the add button for a specific invoice.');
        }
        $flag = false;
        $admission = null;
        DB::beginTransaction();

        try {


            $user_id = auth()->user()->id;
            $patient_id = $patient->id;
            $total_all_amount = 0;
            $invoice = Invoice::create([
                'user_id' => $user_id,
                'patient_id' => $patient_id,
                'government_non_government' => $patient->government_non_gov,
            ]);
            foreach ($patient_tests_in_carts as $ptc) {
                $total_amount = 0;
                if ($patient->government_non_gov == 1) {
                    $total_amount = 0;
                    $total_all_amount = $total_all_amount + $total_amount;
                } else {
                    $total_amount = FeeType::find($ptc->fee_type_id)->amount;
                    $total_all_amount = $total_all_amount + $total_amount;
                }
                PatientTest::create([
                    'patient_id' => $ptc->patient_id,
                    'fee_type_id' => $ptc->fee_type_id,
                    'invoice_id' => $invoice->id,
                    'government_non_gov' => $patient->government_non_gov,
                    'total_amount' => $total_amount,
                ]);
            }
            $invoice->total_amount = $total_all_amount;
            $invoice->save();
            foreach ($patient_tests_in_carts as $ptc) {
                $ptc->delete();
            }


            if ($request->has('admission_form') && $request->admission_form == 1) {
                $admission = Admission::create([
                    'user_id' => $user_id,
                    'invoice_id' => $invoice->id,
                    'patient_id' => $patient_id,
                    'unit_ward' => $request->unit_ward,
                    'disease' => $request->disease,
                    'category' => $request->category,
                    'nok_name' => $request->nok_name,
                    'relation_with_patient' => $request->relation_with_patient,
                    'address' => $request->address,
                    'cell_no' => $request->cell_no,
                    'cnic_no' => $request->cnic_no,
                ]);
            }


            $flag = true;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

        if ($flag) {
            return to_route('patient.patient_invoice', [$patient->id, $invoice->id]);
        } else {
            return to_route('patient.proceed', $patient->id)->with('message', 'Something went wrong, please try again.');
        }

    }


    public function patient_invoice(Patient $patient, Invoice $invoice)
    {


        $date_of_day = Carbon::parse($invoice->created_at)->format('Y-m-d');
        $fee_type_id = $invoice->patient_test_latest->fee_type_id;
        $patient_test_latest_id = $invoice->patient_test_latest->id;


        $result = DB::table('patient_tests')
            ->where('fee_type_id', $fee_type_id)
            ->whereDate('created_at', $date_of_day)
            ->orderByDesc('created_at')
            ->select('*', DB::raw('ROW_NUMBER() OVER (ORDER BY created_at) AS count_no'))
            ->get();

        $chitNumber = $result->where('id', $patient_test_latest_id)->first()->count_no;


        $total_amount = $invoice->patient_test->sum('total_amount');
        $department = null;
        $fee_category = null;
        $fee_category_main = null;
        if (!empty($invoice->patient_test_latest->fee_type->feeCategory)) {
            $department = $invoice->patient_test_latest->fee_type->feeCategory->name;
        }
        if (!empty($invoice->patient_test_latest->fee_type)) {
            $fee_category = $invoice->patient_test_latest->fee_type->type;
            $fee_category_main = FeeCategory::find($invoice->patient_test_latest->fee_type->fee_category_id)->name;
        }


//        dd($invoice->patient_test->groupBy('fee_type_id'));
        return view('patient.invoice', compact('patient', 'patient', 'fee_category_main', 'invoice', 'total_amount', 'department', 'fee_category', 'chitNumber'));
    }


    public function patient_history(Patient $patient)
    {

        $patient_tests = PatientTest::where('patient_id', $patient->id)
            ->groupBy('patient_test_id')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('patient.history', compact('patient_tests'));
    }


    public function patient_test_invoice_generate(\Illuminate\Http\Request $request)
    {
        // login user id capture
        $request->merge(['user_id' => auth()->user()->id]);
        foreach ($request->patient_test as $pt) {
            PatientTestCart::create([
                'patient_id' => $request->patient_id,
                'lab_test_id' => $pt,
            ]);
        }
        return to_route('patient.proceed', $request->patient_id)->with('message', 'Patient test added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return view('patient.show', compact('patient'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('patient.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        if ($request->input('mobile_alert')) {
            $request->merge(['mobile_alert' => 1]);
        } else {
            $request->merge(['mobile_alert' => 0]);
        }


        if ($request->input('email_alert')) {
            $request->merge(['email_alert' => 1]);
        } else {
            $request->merge(['email_alert' => 0]);
        }

//        dd($request->all());
        $patient->update($request->all());
        return redirect()->route('patient.index')->with('message', 'Patient updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }


    public function patient_actions(Patient $patient)
    {
        return view('patient.actions', compact('patient'));
    }

    private function getAutoGender($title)
    {
        // List of titles considered as male
        $maleTitles = ['Mr.', 'S/O', 'F/O'];

        // List of titles considered as female
        $femaleTitles = ['H/O', 'W/O', 'Miss', 'D/O', 'M/O'];

        if (in_array($title, $maleTitles)) {
            return '1';
        } elseif (in_array($title, $femaleTitles)) {
            return '0';
        }
    }
}

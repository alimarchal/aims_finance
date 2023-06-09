<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChitRequest;
use App\Http\Requests\UpdateChitRequest;
use App\Models\Chit;
use App\Models\FeeType;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChitController extends Controller
{
    public function issued_chits(Patient $patient)
    {
        return view('chit.issued_chits', compact('patient'));
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
        ]);
        // login user id capture
        $request->merge(['user_id' => auth()->user()->id]);

        DB::beginTransaction();

        try {

            $amount = null;
            $fee_type_id = null;
            $ipd_opd = $request->ipd_opd;

            if ($request->input('government_department_id')) {
                $amount = 0.00;
            } else {
                if ($request->ipd_opd == 0) {
                    $amount = FeeType::find(1)->amount;
                    $request->merge(['fee_type_id' => 1]);
                    $ipd_opd = 0;
                } else {
                    $amount = FeeType::find(107)->amount;
                    $request->merge(['fee_type_id' => 107]);
                }
            }
//            dd($request->all());
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
                'ipd_opd' => $ipd_opd,
                'payment_status' => 1,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }

//        foreach ($request->patient_test as $pt) {
//            PatientTestCart::create([
//                'patient_id' => $patient->id,
//                'lab_test_id' => $pt,
//            ]);
//        }
        return to_route('chit.print', [$patient->id, $chit->id]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        return view('chit.print', compact('chit', 'patient'));
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

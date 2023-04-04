<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\PatientTest;
use App\Models\PatientTestCart;
use Spatie\QueryBuilder\QueryBuilder;


class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = QueryBuilder::for(Patient::class)
            ->allowedFilters(['name', 'father_son_do', 'sex', 'cnic', 'mobile_no', 'government_non_gov'])
            ->get();
        return view('patient.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patient.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        // login user id capture
        $request->merge(['user_id' => auth()->user()->id]);
        $patient = Patient::create($request->all());
        foreach ($request->patient_test as $pt) {
            PatientTestCart::create([
                'patient_id' => $patient->id,
                'lab_test_id' => $pt,
            ]);
        }
        return to_route('patient.proceed', $patient->id)->with('message', 'Patient created successfully!');
    }

    public function proceed(Patient $patient)
    {

        return view('patient.proceed', compact('patient'));
    }


    public function proceed_cart_destroy(PatientTestCart $patientTestCart)
    {

        $patient_id = $patientTestCart->patient_id;
        $patientTestCart->delete();
        return to_route('patient.proceed', $patient_id)->with('message', 'Lab test deleted successfully!');
    }


    public function proceed_to_invoice(\Illuminate\Http\Request $request, Patient $patient)
    {

        $request->validate([
            'terms' => 'required',
        ]);
        $patientTestCart = PatientTestCart::where('patient_id', $patient->id)->get();
        $patient_test_id = $patient->id . '-' . date('d-m-Y-His');

        if ($patient->government_non_gov == 1) {

            foreach ($patientTestCart as $ptc) {
                PatientTest::create([
                    'patient_test_id' => $patient_test_id,
                    'user_id' => auth()->user()->id,
                    'department_id' => auth()->user()->department_id,
                    'patient_id' => $ptc->patient_id,
                    'lab_test_id' => $ptc->lab_test_id,
                    'hif_amount' => 0.00,
                    'government_amount' => 0.00,
                    'total_amount' => 0.00,
                    'government_non_gov' => 1
                ]);
            }
        } else {
            foreach ($patientTestCart as $ptc) {
                PatientTest::create([
                    'patient_test_id' => $patient_test_id,
                    'user_id' => auth()->user()->id,
                    'department_id' => auth()->user()->department_id,
                    'patient_id' => $ptc->patient_id,
                    'lab_test_id' => $ptc->lab_test_id,
                    'hif_amount' => $ptc->lab_test->hif_fee,
                    'government_amount' => $ptc->lab_test->government_fee,
                    'total_amount' => $ptc->lab_test->total_fee,
                    'government_non_gov' => 0
                ]);
            }
        }
        foreach ($patientTestCart as $ptc) {
            $ptc->delete();
        }
        return to_route('patient.patient_invoice', [$patient->id, $patient_test_id])->with('message', 'Lab test invoice generated successfully!');
    }


    public function patient_invoice(Patient $patient, $patient_test_id)
    {
        $patient_test = PatientTest::where('patient_test_id', $patient_test_id)->get();
        return view('patient.invoice', compact('patient', 'patient_test_id', 'patient_test'));
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
}

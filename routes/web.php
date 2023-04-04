<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('department',\App\Http\Controllers\DepartmentController::class);
    Route::resource('labTest',\App\Http\Controllers\LabTestController::class);
    Route::get('patient/{patient}/proceed',[\App\Http\Controllers\PatientController::class, 'proceed'])->name('patient.proceed');
    Route::delete('patient/{patientTestCart}',[\App\Http\Controllers\PatientController::class, 'proceed_cart_destroy'])->name('patient_cart.destroy');
    Route::post('patient/{patient}/generateInvoice',[\App\Http\Controllers\PatientController::class, 'proceed_to_invoice'])->name('patient.proceed_to_invoice');
    Route::get('patient/{patient}/{invoiceNo}/show',[\App\Http\Controllers\PatientController::class, 'patient_invoice'])->name('patient.patient_invoice');
    Route::get('patient/{patient}/history',[\App\Http\Controllers\PatientController::class, 'patient_history'])->name('patient.history');
    Route::post('patient/invoice',[\App\Http\Controllers\PatientController::class, 'patient_test_invoice_generate'])->name('patient.patient_test_invoice_generate');
    Route::resource('patient',\App\Http\Controllers\PatientController::class);
});

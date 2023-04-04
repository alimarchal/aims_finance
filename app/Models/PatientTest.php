<?php

namespace App\Models;

use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientTestController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientTest extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_test_id',
        'user_id',
        'department_id',
        'patient_id',
        'lab_test_id',
        'hif_amount',
        'government_amount',
        'total_amount',
        'government_non_gov',
    ];

    public function lab_test(): BelongsTo
    {
        return $this->belongsTo(LabTest::class,'lab_test_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class,'patient_id');
    }
}

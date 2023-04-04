<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTestCart extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'lab_test_id',
    ];

    public function lab_test()
    {
        return $this->belongsTo(LabTest::class);
    }
}

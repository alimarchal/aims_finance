<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTestCart extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'fee_type_id',
    ];

    public function fee_type()
    {
        return $this->belongsTo(FeeType::class);
    }
}

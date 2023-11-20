<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_id',
        'government_non_government',
        'total_amount',
    ];

    public function patient_test(): HasMany
    {
        return $this->hasMany(PatientTest::class);
    }


    public function patient_test_sum()
    {
        return $this->hasMany(PatientTest::class)->sum('total_amount');
    }

}

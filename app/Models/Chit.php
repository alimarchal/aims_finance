<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'patient_id',
        'fee_type_id',
        'issued_date',
        'amount',
        'amount_hif',
        'ipd_opd',
        'payment_status',
        'government_non_gov',
        'government_department_id',
        'government_card_no',
        'designation',
    ];


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'patient_id',
        'unit_ward',
        'disease',
        'category',
        'nok_name',
        'relation_with_patient',
        'address',
        'cell_no',
        'cnic_no',
    ];
}

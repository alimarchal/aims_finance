<?php

namespace App\Models;

use App\Http\Controllers\PatientController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'father_son_do',
        'sex',
        'cnic',
        'mobile_no',
        'government_non_gov',
        'department_name',
        'government_card_no',
    ];


    public function patient_test_cart()
    {
        return $this->hasMany(PatientTestCart::class);
    }
}

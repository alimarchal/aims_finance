<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_category_id',
        'type',
        'amount',
        'status',
    ];

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }
}

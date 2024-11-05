<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'amount',
        'repayment_date',
        'status'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}


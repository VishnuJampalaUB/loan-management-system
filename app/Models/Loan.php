<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'amount',
        'interest_rate',
        'duration',
        'lender_id',
        'borrower_id',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Accessor for amount to format it as a dollar value.
     * This assumes the amount is stored directly in dollars.
     */
    public function getAmountAttribute($value)
    {
        return number_format($value, 2); // Format as dollars (e.g., 12.50)
    }

    /**
     * Accessor for interest rate to format it as a percentage.
     * This assumes the interest rate is stored as a decimal (e.g., 5.5 for 5.5%).
     */
    public function getInterestRateAttribute($value)
    {
        return $value . '%'; // Append % sign for readability
    }

    /**
     * Mutator for interest rate to store as a decimal.
     */
    public function setInterestRateAttribute($value)
    {
        $this->attributes['interest_rate'] = $value; // Store as a number without the % sign
    }

    /**
     * Accessor for created_at to format it according to application's timezone.
     */
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->timezone(config('app.timezone'))->toDateTimeString();
    }

    /**
     * Accessor for updated_at to format it according to application's timezone.
     */
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Attributes to hide from serialized outputs
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Loans where the user is the lender
    public function lentLoans()
    {
        return $this->hasMany(Loan::class, 'lender_id');
    }

    // Loans where the user is the borrower
    public function borrowedLoans()
    {
        return $this->hasMany(Loan::class, 'borrower_id');
    }
}

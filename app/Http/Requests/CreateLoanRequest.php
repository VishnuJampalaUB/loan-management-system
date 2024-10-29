<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Rules\DifferentLenderBorrowerRule;

class CreateLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only allow authenticated users to create a loan
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'duration' => 'required|integer',
            'borrower_id' => [
                'required',
                'exists:users,id',
                new DifferentLenderBorrowerRule(), // Custom rule to ensure lender and borrower are different
            ],
        ];
    }
}

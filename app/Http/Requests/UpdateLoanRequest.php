<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLoanRequest extends FormRequest
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
        ];
    }
}

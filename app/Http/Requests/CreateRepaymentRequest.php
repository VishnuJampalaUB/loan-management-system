<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRepaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorize all users; you may adjust based on your logic
    }

    public function rules(): array
    {
        return [
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'in:initiated,pending,completed,failed',
        ];
    }
}

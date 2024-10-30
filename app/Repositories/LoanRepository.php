<?php

namespace App\Repositories;

use App\Models\Loan;

class LoanRepository
{
    protected $model;

    // Initialize repository with Loan model instance
    public function __construct(Loan $loan)
    {
        $this->model = $loan;
    }

    // Retrieve all loans with lender and borrower names
    public function findAll()
    {
        return $this->model->with(['lender:id,name', 'borrower:id,name'])->get();
    }

    // Create a new loan with the provided data
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // Find a specific loan by ID, including lender and borrower names
    public function findById($id)
    {
        return $this->model->with(['lender:id,name', 'borrower:id,name'])->findOrFail($id);
    }

    // Update a loan by ID if the lender is the authenticated user
    public function update($id, array $data, $lenderId)
    {
        $loan = $this->model->where('id', $id)->where('lender_id', $lenderId)->firstOrFail();
        $loan->update($data);

        return $loan;
    }

    // Delete a loan by ID if the lender is the authenticated user
    public function delete($id, $lenderId)
    {
        $loan = $this->model->where('id', $id)->where('lender_id', $lenderId)->firstOrFail();
        $loan->delete();
    }
}

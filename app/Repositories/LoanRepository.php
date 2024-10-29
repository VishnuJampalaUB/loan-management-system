<?php

namespace App\Repositories;

use App\Models\Loan;

class LoanRepository
{
    protected $model;

    public function __construct(Loan $loan)
    {
        $this->model = $loan;
    }

    public function findAll()
    {
        // Eager load lender and borrower names
        return $this->model->with(['lender:id,name', 'borrower:id,name'])->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function findById($id)
    {
        // Eager load lender and borrower names
        return $this->model->with(['lender:id,name', 'borrower:id,name'])->findOrFail($id);
    }

    public function update($id, array $data, $lenderId)
    {
        $loan = $this->model->where('id', $id)->where('lender_id', $lenderId)->firstOrFail();
        $loan->update($data);

        return $loan;
    }

    public function delete($id, $lenderId)
    {
        $loan = $this->model->where('id', $id)->where('lender_id', $lenderId)->firstOrFail();
        $loan->delete();
    }
}

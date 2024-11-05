<?php

namespace App\Repositories;

use App\Models\Repayment;

class RepaymentRepository
{
    protected $model;

    public function __construct(Repayment $repayment)
    {
        $this->model = $repayment;
    }

    public function getAllByLoanId($loanId)
    {
        return $this->model->where('loan_id', $loanId)->get();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function updateStatus($id, $status)
    {
        $repayment = $this->model->findOrFail($id);
        $repayment->update(['status' => $status]);

        return $repayment;
    }
}

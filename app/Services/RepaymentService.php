<?php

namespace App\Services;

use App\Repositories\RepaymentRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RepaymentService
{
    protected $repaymentRepo;

    public function __construct(RepaymentRepository $repaymentRepo)
    {
        $this->repaymentRepo = $repaymentRepo;
    }

    public function getRepaymentsByLoanId($loanId)
    {
        return $this->repaymentRepo->getAllByLoanId($loanId);
    }

    public function createRepayment(array $data)
    {
        return $this->repaymentRepo->create($data);
    }

    public function updateRepaymentStatus($id, $status)
    {
        return $this->repaymentRepo->updateStatus($id, $status);
    }
}

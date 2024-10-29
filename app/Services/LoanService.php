<?php

namespace App\Services;

use App\Repositories\LoanRepository;

class LoanService
{
    protected LoanRepository $loanRepo;

    public function __construct(LoanRepository $loanRepo)
    {
        $this->loanRepo = $loanRepo;
    }

    public function getAllLoans()
    {
        return $this->loanRepo->findAll();
    }

    public function createLoan(array $data, $lenderId)
    {
        $data['lender_id'] = $lenderId;
        $loan = $this->loanRepo->create($data);

        return ['loan_id' => $loan->id];
    }

    public function getLoanById($id)
    {
        return $this->loanRepo->findById($id);
    }

    public function updateLoan($id, array $data, $lenderId)
    {
        return $this->loanRepo->update($id, $data, $lenderId);
    }

    public function deleteLoan($id, $lenderId)
    {
        return $this->loanRepo->delete($id, $lenderId);
    }
}

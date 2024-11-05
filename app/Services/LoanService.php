<?php

namespace App\Services;

use App\Repositories\LoanRepository;

class LoanService
{
    protected LoanRepository $loanRepo;

    // Inject the LoanRepository dependency
    public function __construct(LoanRepository $loanRepo)
    {
        $this->loanRepo = $loanRepo;
    }

    // Fetch all loans, including lender and borrower names
    public function getAllLoans()
    {
        return $this->loanRepo->findAll();
    }

    // Create a new loan associated with a specific lender
    public function createLoan(array $data, $lenderId)
    {
        $data['lender_id'] = $lenderId;
        $loan = $this->loanRepo->create($data);

        return ['loan_id' => $loan->id]; // Return created loan ID
    }

    // Retrieve a loan by its ID, including lender and borrower details
    public function getLoanById($id)
    {
        return $this->loanRepo->findById($id);
    }

    // Update loan details if the request comes from the lender who created it
    public function updateLoan($id, array $data, $lenderId)
    {
        return $this->loanRepo->update($id, $data, $lenderId);

        //
    }

    // Delete a loan if the request comes from the lender who created it
    public function deleteLoan($id, $lenderId)
    {
        return $this->loanRepo->delete($id, $lenderId);
    }
}

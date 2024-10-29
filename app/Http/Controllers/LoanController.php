<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Throwable;

class LoanController extends Controller
{
    protected $loanService;

    /**
     * LoanController constructor.
     *
     * @param LoanService $loanService Injected LoanService to handle loan business logic
     */
    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Retrieve all loans.
     *
     * @return JsonResponse JSON response containing all loans or an error message
     */
    public function index(): JsonResponse
    {
        try {
            $loans = $this->loanService->getAllLoans();
            return $this->sendResponse($loans, 'Loans retrieved successfully.');
        } catch (Throwable $e) {
            // Return error response if fetching loans fails
            return $this->sendError($e, 'Failed to retrieve loans');
        }
    }

    /**
     * Store a new loan.
     *
     * @param CreateLoanRequest $request Validated request for creating a loan
     * @return JsonResponse JSON response containing the created loan ID or an error message
     */
    public function store(CreateLoanRequest $request): JsonResponse
    {
        try {
            // Use validated data to create a loan, associating with the current user as lender
            $loan = $this->loanService->createLoan($request->validated(), $request->user()->id);
            return $this->sendResponse($loan, 'Loan created successfully.', 201);
        } catch (Throwable $e) {
            // Return error response if loan creation fails
            return $this->sendError($e, 'Failed to create loan');
        }
    }

    /**
     * Show details of a specific loan.
     *
     * @param int $id The ID of the loan to retrieve
     * @return JsonResponse JSON response containing loan details or an error message
     */
    public function show($id): JsonResponse
    {
        try {
            $loan = $this->loanService->getLoanById($id);
            return $this->sendResponse($loan, 'Loan retrieved successfully.');
        } catch (Throwable $e) {
            // Return error response if loan retrieval fails
            return $this->sendError($e, 'Failed to retrieve loan');
        }
    }

    /**
     * Update an existing loan.
     *
     * @param UpdateLoanRequest $request Validated request data for updating a loan
     * @param int $id The ID of the loan to update
     * @return JsonResponse JSON response indicating success or failure of the update operation
     */
    public function update(UpdateLoanRequest $request, $id): JsonResponse
    {
        try {
            // Update loan using validated data and ensure the current user is the lender
            $this->loanService->updateLoan($id, $request->validated(), $request->user()->id);
            return $this->sendResponse(null, 'Loan updated successfully.');
        } catch (Throwable $e) {
            // Return error response if loan update fails
            return $this->sendError($e, 'Failed to update loan');
        }
    }

    /**
     * Delete a loan.
     *
     * @param int $id The ID of the loan to delete
     * @return JsonResponse JSON response indicating success or failure of the delete operation
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Delete the loan, ensuring the current user is authorized as the lender
            $this->loanService->deleteLoan($id, request()->user()->id);
            return $this->sendResponse(null, 'Loan deleted successfully.');
        } catch (Throwable $e) {
            // Return error response if loan deletion fails
            return $this->sendError($e, 'Failed to delete loan');
        }
    }
}

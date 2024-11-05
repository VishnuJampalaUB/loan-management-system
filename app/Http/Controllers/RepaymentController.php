<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRepaymentRequest;
use App\Services\RepaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class RepaymentController extends Controller
{
    protected $repaymentService;

    public function __construct(RepaymentService $repaymentService)
    {
        $this->repaymentService = $repaymentService;
    }

    /**
     * List repayments for a specific loan based on the loan_id query parameter.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $loanId = $request->query('loan_id');

        try {
            $repayments = $this->repaymentService->getRepaymentsByLoanId($loanId);
            return $this->sendResponse($repayments, 'Repayments retrieved successfully.');
        } catch (Throwable $e) {
            return $this->sendError($e, 'Failed to retrieve repayments.');
        }
    }

    /**
     * Store a new repayment entry.
     *
     * @param CreateRepaymentRequest $request
     * @return JsonResponse
     */
    public function store(CreateRepaymentRequest $request): JsonResponse
    {
        try {
            $repayment = $this->repaymentService->createRepayment($request->validated());
            return $this->sendResponse($repayment, 'Repayment created successfully.', 201);
        } catch (Throwable $e) {
            return $this->sendError($e, 'Failed to create repayment.');
        }
    }
}

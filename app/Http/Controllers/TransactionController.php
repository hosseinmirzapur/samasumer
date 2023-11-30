<?php

namespace App\Http\Controllers;

use App\Http\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TransactionController extends Controller
{
    protected TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function userTxs(): JsonResponse
    {
        $response = $this->service->userTxs();
        return successResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->query('q');
        $response = $this->service->search($q);
        return successResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function checkTx(Request $request): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required',
            'tx_id' => 'required'
        ]);
        $this->service->checkTx($data);
        return successResponse();
    }
}

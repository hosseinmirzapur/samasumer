<?php

namespace App\Http\Controllers;

use App\Http\Services\WalletService;
use App\Notifications\TransactionHappened;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected WalletService $service;

    public function __construct(WalletService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        $response = $this->service->getInfo();
        return successResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deposit(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'min:2000']
        ]);
        $this->service->deposit($data);
        return successResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function withdraw(Request $request): JsonResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'min:0']
        ]);
        $this->service->withdraw($data);
        return successResponse();
    }
}

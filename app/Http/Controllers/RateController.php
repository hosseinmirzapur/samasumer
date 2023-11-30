<?php

namespace App\Http\Controllers;

use App\Http\Services\RateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RateController extends Controller
{
    protected RateService $service;

    public function __construct(RateService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function rateHotel(Request $request): JsonResponse
    {
        $data = $request->validate([
            'hotel_id' => ['required', Rule::exists('hotels', 'id')],
            'amount' => ['required', 'min:0', 'max:5', 'numeric']
        ]);
        $this->service->rateHotel($data['hotel_id'], $data['amount']);
        return successResponse();
    }
}

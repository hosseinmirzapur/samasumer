<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\HotelierProfileRequest;
use App\Http\Services\HotelierProfileService;
use Illuminate\Http\JsonResponse;

class HotelierProfileController extends Controller
{
    protected HotelierProfileService $service;

    public function __construct(HotelierProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * @param HotelierProfileRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function alter(HotelierProfileRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->service->alter($data);
        return successResponse();
    }

    /**
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        return successResponse($this->service->profile());
    }
}

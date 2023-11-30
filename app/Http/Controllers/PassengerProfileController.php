<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\AlterProfileRequest;
use App\Http\Services\PassengerProfileService;
use Illuminate\Http\JsonResponse;

class PassengerProfileController extends Controller
{
    protected PassengerProfileService $service;

    public function __construct(PassengerProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * @param AlterProfileRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function alter(AlterProfileRequest $request): JsonResponse
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
        $response = $this->service->profile();
        return successResponse($response);
    }
}

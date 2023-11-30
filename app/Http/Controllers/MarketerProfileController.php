<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\AlterMarketerProfileRequest;
use App\Http\Services\MarketerProfileService;
use Illuminate\Http\JsonResponse;

class MarketerProfileController extends Controller
{
    protected MarketerProfileService $service;

    public function __construct(MarketerProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * @param AlterMarketerProfileRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function alter(AlterMarketerProfileRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->service->alter($data);
        return successResponse();
    }

    /**
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        return successResponse($this->service->getInfo());
    }
}

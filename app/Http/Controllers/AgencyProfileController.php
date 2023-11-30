<?php

namespace App\Http\Controllers;


use App\Exceptions\CustomException;
use App\Http\Requests\AlterAgencyProfileRequest;
use App\Http\Services\AgencyProfileService;
use Illuminate\Http\JsonResponse;

class AgencyProfileController extends Controller
{
    protected AgencyProfileService $service;

    public function __construct(AgencyProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * @param AlterAgencyProfileRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function alter(AlterAgencyProfileRequest $request): JsonResponse
    {
        $this->service->alter($request->validated());
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

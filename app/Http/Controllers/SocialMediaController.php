<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialMediaRequest;
use App\Http\Services\SocialMediaService;
use Illuminate\Http\JsonResponse;

class SocialMediaController extends Controller
{
    protected SocialMediaService $service;

    public function __construct(SocialMediaService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        return successResponse($this->service->getInfo());
    }

    /**
     * @param SocialMediaRequest $request
     * @return JsonResponse
     */
    public function setInfo(SocialMediaRequest $request): JsonResponse
    {
        $this->service->setInfo($request->validated());
        return successResponse();
    }
}

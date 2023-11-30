<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\AlterHotelRequest;
use App\Http\Services\HotelService;
use App\Models\HotelImage;
use Illuminate\Http\JsonResponse;
use Throwable;

class HotelController extends Controller
{
    protected HotelService $service;

    public function __construct(HotelService $service)
    {
        $this->service = $service;
    }

    /**
     * @param AlterHotelRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function alter(AlterHotelRequest $request): JsonResponse
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

    /**
     * @param HotelImage $image
     * @return JsonResponse
     * @throws Throwable
     */
    public function deleteImage(HotelImage $image): JsonResponse
    {
        $this->service->deleteImage($image);
        return successResponse();
    }

    /**
     * @param HotelImage $image
     * @return JsonResponse
     */
    public function setImageAsMain(HotelImage $image): JsonResponse
    {
        $this->service->setImageAsMain($image);
        return successResponse();
    }
}

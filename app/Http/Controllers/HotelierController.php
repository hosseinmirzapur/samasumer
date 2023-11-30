<?php

namespace App\Http\Controllers;

use App\Http\Requests\HotelierPasswordRequest;
use App\Http\Requests\PassengerEnterRequest;
use App\Http\Services\HotelierService;
use App\Rules\MobileNumberRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Throwable;

class HotelierController extends Controller
{
    protected HotelierService $service;

    public function __construct(HotelierService $service)
    {
        $this->service = $service;
    }

    /**
     * @param PassengerEnterRequest $request
     * @return JsonResponse
     */
    public function enter(PassengerEnterRequest $request): JsonResponse
    {
        return successResponse($this->service->handleEnter($request->validated()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => 'required',
            'code' => ['required', 'digits:5'],
            'mobile' => ['required', new MobileNumberRule]
        ]);
        return successResponse($this->service->verifyOtp($data));
    }

    /**
     * @param HotelierPasswordRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function enterPassword(HotelierPasswordRequest $request): JsonResponse
    {
        return successResponse($this->service->enterPassword($request->validated()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function changePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'old_pass' => ['required', 'min:8'],
            'new_pass' => ['required', Password::min(8)->letters()->numbers()]
        ]);
        $this->service->changePass($data);
        return successResponse();
    }
}

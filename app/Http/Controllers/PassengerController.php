<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\PassengerEnterRequest;
use App\Http\Requests\PassengerPasswordRequest;
use App\Http\Services\PassengerService;
use App\Rules\MobileNumberRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Throwable;

class PassengerController extends Controller
{
    protected PassengerService $service;

    public function __construct(PassengerService $service)
    {
        $this->service = $service;
    }

    /**
     * @param PassengerEnterRequest $request
     * @return JsonResponse
     */
    public function enter(PassengerEnterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->service->handleEnter($data);
        return successResponse($response);
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
        $response = $this->service->verifyOtp($data);
        return successResponse($response);
    }

    /**
     * @param PassengerPasswordRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws CustomException
     */
    public function enterPassword(PassengerPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $response = $this->service->enterPassword($data);
        return successResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function changePass(Request $request): JsonResponse
    {
        $data = $request->validate([
            'old_pass' => ['required', 'min:8'],
            'new_pass' => ['required', Password::min(8)->letters()->numbers()]
        ]);
        $this->service->changePass($data);
        return successResponse();
    }
}

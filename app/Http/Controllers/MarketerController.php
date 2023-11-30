<?php

namespace App\Http\Controllers;

use App\Http\Services\MarketerService;
use App\Rules\MobileNumberRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Throwable;

class MarketerController extends Controller
{
    protected MarketerService $service;

    public function __construct(MarketerService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function enter(Request $request): JsonResponse
    {
        $data = $request->validate([
            'mobile' => ['required', new MobileNumberRule]
        ]);
        return successResponse($this->service->handleEnter($data));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'digits:5'],
            'token' => 'required',
            'mobile' => ['required', new MobileNumberRule]
        ]);
        return successResponse($this->service->verifyOTP($data));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function enterPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => 'required',
            'mobile' => ['required', new MobileNumberRule],
            'password' => ['required', Password::min(8)->letters()->numbers()],
            'referral_code' => 'nullable'
        ]);
        return successResponse($this->service->enterPass($data));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function changePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required'
        ]);
        $this->service->changePass($data);
        return successResponse();
    }
}

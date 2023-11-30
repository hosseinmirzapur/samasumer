<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\AddChildAgencyRequest;
use App\Http\Services\AgencyService;
use App\Models\Agency;
use App\Rules\MobileNumberRule;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Throwable;

class AgencyController extends Controller
{
    protected AgencyService $service;

    public function __construct(AgencyService $service)
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
        return successResponse($this->service->enter($data));
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
        return successResponse($this->service->verifyOtp($data));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     * @throws CustomException
     */
    public function enterPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => 'required',
            'type' => ['required', Rule::in(Agency::TYPE)],
            'password' => ['required', Password::min(8)->numbers()->letters()],
            'referral_code' => 'nullable',
            'mobile' => ['required', new MobileNumberRule]
        ]);
        return successResponse($this->service->enterPassword($data));
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
            'new_pass' => ['required', Password::min(8)->letters()->numbers()]
        ]);
        $this->service->changePass($data);
        return successResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function childAgencies(Request $request): JsonResponse
    {
        // Request query params
        $all = $request->query('all') ?? true;
        $min = $request->query('min') ?? 'lowest';
        $max = $request->query('max') ?? 'highest';

        // Validation of query params
        Validator::validate(
            [
                'all' => $all,
                'min' => $min,
                'max' => $max
            ],
            [
                'all' => ['required', 'boolean'],
                'min' => 'required',
                'max' => 'required'
            ]);

        $result = $this->service->getChildAgencies($all, $min, $max);

        return successResponse($result);
    }

    /**
     * @param AddChildAgencyRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function addChild(AddChildAgencyRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->service->addChild($data);
        return successResponse();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $q = $request->query('q') ?? null;
        $result = $this->service->searchChildren($q);
        return successResponse($result);
    }
}

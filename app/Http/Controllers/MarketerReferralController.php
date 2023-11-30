<?php

namespace App\Http\Controllers;


use App\Http\Requests\MarketerReferralRequest;
use App\Http\Services\MarketerReferralService;

class MarketerReferralController extends Controller
{
    protected MarketerReferralService $service;

    public function __construct(MarketerReferralService $service)
    {
        $this->service = $service;
    }

    public function add(MarketerReferralRequest $request)
    {
        $data = $request->validated();
        $type = $data['type'];
        if ($type == 'hotelier') {
            $this->service->handleHotelier($data);
        } else $this->service->handleAgency($data);

        return successResponse();
    }

    public function list()
    {

    }
}

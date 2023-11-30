<?php

namespace App\Http\Controllers;

use App\Http\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function userNotifs(Request $request): JsonResponse
    {
        $count = $request->query('count') ?? null;
        $response = $this->service->userNotifs($count);
        return successResponse($response);
    }

    /**
     * @return JsonResponse
     */
    public function markNotifsAsRead(): JsonResponse
    {
        $this->service->markAllAsRead();
        return successResponse();
    }
}

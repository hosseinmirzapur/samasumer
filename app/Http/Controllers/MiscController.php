<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class MiscController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return successResponse();
    }
}

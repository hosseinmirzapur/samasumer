<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Throwable;

class EnsureAdminRequests
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): mixed
    {
        throw_if(currentUser() instanceof Admin, new CustomException('UNAUTHORIZED'));
        return $next($request);
    }
}

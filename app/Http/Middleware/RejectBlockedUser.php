<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Illuminate\Http\Request;
use Throwable;

class RejectBlockedUser
{
    /**
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next)
    {
        throw_if($request->user()->blocked, new CustomException('BLOCKED_USER'));
        return $next($request);
    }
}

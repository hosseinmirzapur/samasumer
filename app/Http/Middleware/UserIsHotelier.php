<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use App\Models\Hotelier;
use Closure;
use Illuminate\Http\Request;

class UserIsHotelier
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws CustomException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (currentUser() instanceof Hotelier) {
            return $next($request);
        }
        throw new CustomException('UNAUTHORIZED');
    }
}

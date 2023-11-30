<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use App\Models\Agency;
use App\Models\Hotelier;
use App\Models\Marketer;
use App\Models\Passenger;
use Closure;
use Illuminate\Http\Request;
use Throwable;

class UserTypeMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param $type
     * @return mixed
     * @throws CustomException
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next, $type): mixed
    {
        switch ($type) {
            case 'passenger':
                throw_if(!(currentUser() instanceof Passenger), new CustomException('UNAUTHORIZED'));
                break;
            case 'hotelier':
                throw_if(!(currentUser() instanceof Hotelier), new CustomException('UNAUTHORIZED'));
                break;
            case 'agency':
                throw_if(!(currentUser() instanceof Agency), new CustomException('UNAUTHORIZED'));
                break;
            case 'marketer':
                throw_if(!(currentUser() instanceof Marketer), new CustomException('UNAUTHORIZED'));
                break;
            default:
                throw new CustomException('INVALID_USER_TYPE');
        }
        return $next($request);
    }
}

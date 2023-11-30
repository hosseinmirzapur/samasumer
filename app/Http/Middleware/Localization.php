<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Localization
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('x-locale');
        $this->handleLocale($locale);
        return $next($request);
    }

    protected function handleLocale($locale)
    {
        switch ($locale) {
            case 'fa':
                app()->setLocale('fa');
                break;
            case 'en':
                app()->setLocale('en');
                break;
            case 'ar':
                app()->setLocale('ar');
                break;
            default:
                app()->setLocale('en');
        }
    }
}

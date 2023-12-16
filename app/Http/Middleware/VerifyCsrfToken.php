<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $addHttpCookie = true;
    protected $except = [];
    public function handle($request, Closure $next){
        return $next($request);
    }
}

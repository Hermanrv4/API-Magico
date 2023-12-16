<?php
/**
 * Created by PhpStorm.
 * User: Jawzard
 * Date: 14/02/2019
 * Time: 10:46
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class HttpsProtocol {

    public function handle($request, Closure $next)
    {
        if (!$request->secure() && !config('env.app_debug')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}

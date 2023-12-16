<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\TrimStrings::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\HttpsProtocol::class,

        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \App\Http\Middleware\VerifyCsrfToken::class,

            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\ConfigApiDatabase::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,

        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\GetUserFromToken::class,
        'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,
        'cors' => \App\Http\Middleware\Cors::class,

        'localize'                => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        'localizationRedirect'    => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        'localeSessionRedirect'   => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        'localeCookieRedirect'    => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
        'localeViewPath'          => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
    ];
}

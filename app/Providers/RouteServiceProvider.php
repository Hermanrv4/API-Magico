<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider {
    public function boot(){
        parent::boot();
    }
    public function map(){
        $this->mapApiAdminRoutes();
        $this->mapApiSiteRoutes();
    }
    protected function mapApiAdminRoutes(){
        Route::namespace('App\Http\Modules\Admin\Authentication\Controllers' )->group(base_path('app/Http/Modules/Admin/Authentication/route.php' ));
        Route::namespace('App\Http\Modules\Admin\Extra\Controllers'          )->group(base_path('app/Http/Modules/Admin/Extra/route.php'          ));
        Route::namespace('App\Http\Modules\Admin\Configuration\Controllers'  )->group(base_path('app/Http/Modules/Admin/Configuration/route.php'  ));
        Route::namespace('App\Http\Modules\Admin\Entity\Controllers'         )->group(base_path('app/Http/Modules/Admin/Entity/route.php'  ));
    }
    protected function mapApiSiteRoutes(){
        Route::namespace('App\Http\Modules\Site\Authentication\Controllers' )->group(base_path('app/Http/Modules/Site/Authentication/route.php' ));
        Route::namespace('App\Http\Modules\Site\Configuration\Controllers'  )->group(base_path('app/Http/Modules/Site/Configuration/route.php'  ));
        Route::namespace('App\Http\Modules\Site\Extra\Controllers'          )->group(base_path('app/Http/Modules/Site/Extra/route.php'          ));
        Route::namespace('App\Http\Modules\Site\Entity\Controllers'         )->group(base_path('app/Http/Modules/Site/Entity/route.php'         ));
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ConfigApiDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(isset($request[config('env.app_commerce_param')])){
            DB::purge(env('DB_CONNECTION'));
            $array = [
                'driver' => 'mysql',
                'host' => config('env.'.strtolower($request[config('env.app_commerce_param')]).'_db_host'),
                'port' => config('env.'.strtolower($request[config('env.app_commerce_param')]).'_db_port'),
                'database' => config('env.'.strtolower($request[config('env.app_commerce_param')]).'_db_database'),
                'username' => config('env.'.strtolower($request[config('env.app_commerce_param')]).'_db_username'),
                'password' => config('env.'.strtolower($request[config('env.app_commerce_param')]).'_db_password'),
                'unix_socket' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
            ];		
            Config::set("database.connections.".env('DB_CONNECTION'), $array);
            DB::reconnect(env('DB_CONNECTION'));
        }else{
            abort(404); 
        }
        return $next($request);
    }
}

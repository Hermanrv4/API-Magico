<?php
namespace App\Http\Common\Services;
use App\Http\Models\Database;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RouteService{
    public static function GetApiRoute($type,$module,$route){
        $path = $type.'.route.'.$module;
        if($route == null) return config($path);
        $path = $path.'.';

        $action = array();
        $action["uses"] = config($path.$route.'.action');
        $action["as"] = $type.'.'.$module.'.'.$route;
        $action["middleware"] = array();
        //$action["middleware"][] = 'cors';
        $action["middleware"][] = 'web';
        $action["middleware"][] = 'localize';
        $action["middleware"][] = 'localeSessionRedirect';
        $action["middleware"][] = 'localizationRedirect';
        $action["middleware"][] = 'localeViewPath';
        if(config($path.$route.'.secure')){
            $action["middleware"][] = 'jwt.auth';
        }
        $url = LaravelLocalization::setLocale().'/'.'{'.config('env.app_commerce_param').'}'.'/'.$type.'/'.config($path.'group').'/'.config($path.$route.'.url');
        $method = strtolower(config($path.$route.'.method'));
        return Route::$method($url,$action);
    }
    public static function GetApiSiteRoute($module,$route = null){
        return RouteService::GetApiRoute('site',$module,$route);
    }
    public static function GetApiAdminRoute($module,$route = null){
        return RouteService::GetApiRoute('admin',$module,$route);
    }
}

<?php
namespace App\Http\Modules\Admin\Helpers;

use Carbon\Carbon;
use DateTimeZone;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTHelper{
    public static function GetAuthUser(){
        return JWTAuth::parseToken()->authenticate();
    }
}

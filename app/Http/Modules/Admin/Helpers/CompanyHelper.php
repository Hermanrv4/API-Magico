<?php
namespace App\Http\Modules\Admin\Helpers;

use App\Http\Models\Database\Parameter;
use Carbon\Carbon;
use DateTimeZone;
use Tymon\JWTAuth\Facades\JWTAuth;

class CompanyHelper{
    public static function ValidateDefaultId($request){
        $auth_company_id = JWTHelper::GetAuthUser()["company_id"];
        $auth_company_id = ($auth_company_id==null?Parameter::GetByCode("default_id"):$auth_company_id);
        if($auth_company_id!=Parameter::GetByCode("default_id")) return false; //NO ES USUARIO INTERNO, SE TOMA EL DEFAULT ID COMO -1
        if(isset($request["validate_user"]) && isset($request["company_id"])){
            if($request["validate_user"] && $request["company_id"] == Parameter::GetByCode("default_id")){ //SI ES USUARIO INTERNO, COMPANY ES -1 Y ME PIDEN VALIDAR EL USUARIO
                return true; //ENTONCES SE DEBE TOMAR EL -1 COMO TRAER TODOS...
            }
        }
        return false;
    }
}

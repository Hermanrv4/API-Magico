<?php

namespace App\Http\Modules\Admin\Configuration\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ConfigurationController extends ApiController{
    public function NeedUpdate(Request $request){
        $rq_date = DateHelper::GetDateFromString($request["date"],ENV('APP_DATEFORMAT'));
        $db_date = Parameter::GetLastUpdatedDate();
        return $this->SendSuccessResponse(null,array("need_update" => $db_date>$rq_date));
    }
    public function Get(Request $request){
        return $this->SendSuccessResponse(null,array("last_update"=>Parameter::GetLastUpdatedDate(),"parameters"=>Parameter::all()));
    }
    
    public function GetParameters(Request $request){
        $Parameters = Parameter::GetAllByLanguage();
        return $this->SendSuccessResponse(null,array("meta" => $Parameters));
    }
    public function GetParameterByName(Request $request){
        $value = Parameter::GetByCode($request->code);
        return $this->SendSuccessResponse(null,array("value" => $value));
    }
    public function SaveParameters(Request $request){
        $Parameters = Parameter::GetAllByLanguage();
    }
    
    public function UpdateValueByCode(Request $request){
        try{

            $value = Parameter::UpdateValueByCode($request->code, $request->value);
            return $this->SendSuccessResponse(null,array("result" => $value));
            
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }        
    }
}

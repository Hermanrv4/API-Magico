<?php

namespace App\Http\Modules\Site\Configuration\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use Illuminate\Http\Request;

class ConfigurationController extends ApiController{
    public function NeedUpdate(Request $request){
        $rq_date = DateHelper::GetDateFromString($request["date"],ENV('APP_DATEFORMAT'));
        $db_date = Parameter::GetLastUpdatedDate();
        return $this->SendSuccessResponse(null,array("need_update" => $db_date>$rq_date));
    }
    public function Get(Request $request){
        return $this->SendSuccessResponse(null,array("last_update"=>Parameter::GetLastUpdatedDate(),"parameters"=>Parameter::all()));
    }
}

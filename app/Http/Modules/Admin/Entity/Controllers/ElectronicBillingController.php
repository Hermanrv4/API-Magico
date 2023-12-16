<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\ElectronicBilling;
use Illuminate\Http\Request;
//use App\Http\Modules\Admin\Services\ValidationElectronicBillingSale;

class ElectronicBillingController extends ApiController{

    public function GetCorrelative(Request $request){
        $correlative = ElectronicBilling::GetCorrelative($request['serie']);
        return $this->SendSuccessResponse(null,$correlative);
    }
    
    public function UpdateCorrelative(Request $request){
        try {
            $rsp = ElectronicBilling::QuickUpdate($request['serie'],$request['correlative']);
            if ($rsp) {
                return $this->SendSuccessResponse(null,true);
            } else {
                return $this->SendErrorResponse(null,false);
            }            

        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}
<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Currency;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationCurrency;

class CurrencyController extends ApiController{

    public function Get(Request $request){
        if (isset($request["currency_id"])) {
            return $this->SendSuccessResponse(null,Currency::GetById($request["currency_id"]));
        }else{
            return $this->SendSuccessResponse(null,Currency::all());
        }
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationCurrency::CurrencyRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objCurrency = new Currency();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objCurrency = Currency::GetById($request['id']);
                $objCurrency->code = $request['code'];
                $objCurrency->symbol = $request['symbol'];
                $objCurrency->name = $request['name'];
                $objCurrency->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objCurrency));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function Delete(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationCurrency::CurrencyDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objCurrency = Currency::GetById($request['id']);
                $objCurrency->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objCurrency));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}

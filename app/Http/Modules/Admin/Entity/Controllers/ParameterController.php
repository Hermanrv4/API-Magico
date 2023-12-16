<?php

namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationParameter;

class ParameterController extends ApiController{

    public function GetCodes(Request $request){
        if (isset($request['code'])) {
            return $this->SendSuccessResponse(null,Parameter::GetByCode($request['code']));           
        }
        return $this->SendSuccessResponse(null,Parameter::GetCodes());
    }

    public function GetById(Request $request)
    {
        $parameter = Parameter::GetById($request['parameter_id']);
        return $this->SendSuccessResponse(null,$parameter);
    }

    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationParameter::ParameterRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {                
                $objParameter = Parameter::GetById($request['id']);
                $objParameter->value = (is_null($request['value'])?null:$request['value']);
                $objParameter->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objParameter));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
    public function GetCodesOfValues(Request $request){
        return $this->SendSuccessResponse(null, Parameter::GetByCodeValue($request["code"]));
    }
    public function GetCodeSlideLanding(Request $request){
        $objLanding=Parameter::GetLanding();
        return $this->SendSuccessResponse(null,$objLanding);
    }
    public function RegisterPayMethod(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationParameter::ParameterRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {                
                $objParameter = new Parameter();
                $objParameter->code = $request['code'];
                $objParameter->value = (is_null($request['value'])?null:$request['value']);
                $objParameter->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objParameter));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}
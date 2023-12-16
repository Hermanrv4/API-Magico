<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Models\Database\Suscriber;
use App\Http\Models\Database\Parameter;
use App\Http\Modules\Admin\Services\ValidationSuscriber;

class SuscriberController extends ApiController{
    
    public function Get(Request $request){
        if (isset($request["suscriber_id"])) {
            return $this->SendSuccessResponse(null,Suscriber::GetById($request["suscriber_id"]));
        }else{
            return $this->SendSuccessResponse(null,Suscriber::all());
        }
    }

    public function Register(Request $request){        
        try {
            $msg_validation=null;
            $validator = ValidationSuscriber::SuscriberRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objSuscriber = new Suscriber();
                $is_update = $request['id']!=Parameter::GetByCode('default_id');
                if($is_update) $objSuscriber = Suscriber::GetById($request['id']);
                $objSuscriber->email = $request['email'];
                $objSuscriber->info_suscriber = $request['info_suscriber'];
                $objSuscriber->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objSuscriber));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function Delete(Request $request)
    {
        try {
            $msg_validation = null;
            $validator = ValidationSuscriber::SuscriberDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objSuscriber = Suscriber::GetById($request['id']);
                $objSuscriber->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objSuscriber));
            }
            
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
    public function GetSuscriberDate(Request $request){
        return $this->sendSuccessResponse(null, Suscriber::withoutGlobalScopes()->SuscriberDate($request['date_start'], $request['date_end'])->get());
    }
}
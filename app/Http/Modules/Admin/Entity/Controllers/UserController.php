<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\User;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationUser;

class UserController extends ApiController{

    public function Get(Request $request){
        if (isset($request["user_id"])) {
            return $this->SendSuccessResponse(null,User::GetById($request["user_id"]));
        }else{
            return $this->SendSuccessResponse(null,User::where('is_admin',0)->get());
        }
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationUser::UserRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objUser = new User();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objUser = User::GetById($request['id']);
                $objUser->dni = $request['dni'];
                $objUser->first_name = $request['first_name'];
                $objUser->last_name = $request['last_name'];
                $objUser->phone = $request['phone'];
                $objUser->email = $request['email'];
                if (!$is_update || $request['password']!='' || $request['password'] != null) {
                    $objUser->password = $request['password'];
                }
                $objUser->is_admin = 0;
                $objUser->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objUser));
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
            $validator = ValidationUser::UserDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objUser = User::GetById($request['id']);
                $objUser->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objUser));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
    public function GetUserOfDateRegister(Request $request){
        return $this->SendSuccessResponse(null, User::withoutGlobalScopes()->GetUserOfDateRegister($request['date_start'], $request['date_end'])->get());
    }
    public function GetOrderForUserOfDate(Request $request){
        return $this->SendSuccessResponse(null, User::withoutGlobalScopes()->GetOrderUserOfDate($request["date_start"], $request["date_end"])->get());
    }
    public function GetBillingForUserOfDate(Request $request){
        if($request["option"]=="detail"){
            return $this->SendSuccessResponse(null, User::withoutGlobalScopes()->GetUserBillingUserOfDate($request["date_start"], $request["date_end"])->get());
        }else{
            return $this->SendSuccessResponse(null, User::withoutGlobalScopes()->GetBillingUserOfDate($request["date_start"], $request["date_end"])->get());
        }
    }
}

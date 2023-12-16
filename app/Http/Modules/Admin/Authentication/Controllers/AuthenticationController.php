<?php

namespace App\Http\Modules\Admin\Authentication\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\StringHelper;
use App\Http\Models\Database\User;
use App\Http\Modules\Admin\Services\ValidationService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends ApiController{
    public function EmailLogin(Request $request){
        $objAdmin = null;
        $token = null;
        $msg_validation = null;

        $validator = ValidationService::UserLoginEmail($request,$msg_validation);
        $validator->after(function($validator) use ($request,$msg_validation,&$objAdmin){
            $objAdmin = User::GetByEmailAndPassword($request["email"],$request["password"]);
			
            if($objAdmin==null) $validator->errors()->add('form',trans($msg_validation.'form.credentials'));
			
            if(!$objAdmin->is_admin)$validator->errors()->add('form',trans($msg_validation.'form.admin'));
        });
        if($validator->fails()){
            return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
        }else{
            return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array("admin"=>$objAdmin,"token"=>JWTAuth::fromUser($objAdmin)));
        }
    }
}

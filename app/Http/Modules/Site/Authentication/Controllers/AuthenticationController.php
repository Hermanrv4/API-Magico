<?php

namespace App\Http\Modules\Site\Authentication\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\StringHelper;
use App\Http\Models\Database\User;
use App\Http\Modules\Site\Services\ValidationService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends ApiController{
    public function CustomerEmailRegister(Request $request){
        $objUser = null;
        $token = null; 
        $msg_validation = null;
        $validator = ValidationService::CustomerRegister($request,$msg_validation);
        $validator->after(function($validator) use ($request,$msg_validation){});
        if($validator->fails()){
            return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
        }else{
            $objUser = new User();
            $objUser->dni = $request["dni"];
            $objUser->first_name = $request["first_name"];
            $objUser->last_name = $request["last_name"];
            $objUser->phone = $request["phone"];
            $objUser->email = $request["email"];
            $objUser->password = $request["password"];
            $objUser->save();
            return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array("user"=>$objUser,"token"=>JWTAuth::fromUser($objUser),"is_new_user"=>true));
        }
    }
    public function CustomerEmailLogin(Request $request){
        $objUser = null;
        $token = null;
        $msg_validation = null;
        if(!isset($request["is_password"])){
            $request["is_password"] = 1;
        }
        $validator = null;
        if($request["is_password"]==1){
            $validator = ValidationService::CustomerEmailLogin($request,$msg_validation);
            $validator->after(function($validator) use ($request,$msg_validation,&$objUser){
                $objUser = User::GetByEmailAndPassword($request["email"],$request["password"]); 

                if($objUser==null) $validator->errors()->add('form',trans($msg_validation.'form.credentials'));
            });
            if($validator->fails()){
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array("user"=>$objUser,"token"=>JWTAuth::fromUser($objUser)));
            }
            

        }else{
            $validator = ValidationService::CustomerOnlyEmailLogin($request,$msg_validation);
                $objUser = null;

                    $objUser = User::GetByEmail($request["email"]);
                    if($objUser == null){
                        $objUser = new User();
                        $objUser->first_name = '';
                        $objUser->last_name = '';
                        $objUser->email = $request["email"];
                        $objUser->is_admin = 0;
                        $objUser->save();
                    }
                    $objUser = User::GetByEmail($request["email"]);
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array("user"=>$objUser,"token"=>JWTAuth::fromUser($objUser)));

        }

    }
    public function CustomerFacebookAuth(Request $request){
        $objUser = null;
        $token = null;
        $msg_validation = null;
        $objUser = User::GetByFacebookId($request["facebook_id"]);
        $is_new_user = false;
        if($objUser == null){
            $is_new_user = true;
            $validator = ValidationService::CustomerRegister($request,$msg_validation);
            $validator->after(function($validator) use ($request,$msg_validation,&$objUser){});
            if($validator->fails()){
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                if($objUser==null){
                    $objUser = new User();
                    $objUser->dni = $request["dni"];
                    $objUser->first_name = $request["first_name"];
                    $objUser->last_name = $request["last_name"];
                    $objUser->phone = $request["phone"];
                    $objUser->email = $request["email"];
                    $objUser->password = null;
                    $objUser->facebook_id = $request["facebook_id"];
                    $objUser->save();
                }
            }
        }
        return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array("user"=>$objUser,"token"=>JWTAuth::fromUser($objUser),"is_new_user"=>$is_new_user));
    }

	public function CustomerUpdatePassword(Request $request){
		
		$User = User::GetById($request["id"]);
		
		$dateNow = date('Y-m-d H:i:s');
		$datePrev = date('Y-m-d H:i:s', $request["time"]);
		
		
		$Days = (strtotime($dateNow)-$request["time"])/86400;
		$Days = abs($Days);
		
		if($Days<1){
			if($request["password"] == $request["re_password"]){
			$User->password = $request["password"];
			$User->update();
			
				return $this->SendSuccessResponse(null,array());
			}
			
			
		}
		return $this->SendErrorResponse(null,array());
	}
}

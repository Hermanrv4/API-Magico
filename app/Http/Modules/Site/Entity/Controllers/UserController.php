<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\User;
use App\Http\Models\Database\Address;
use Illuminate\Http\Request;
use App\Http\Modules\Site\Services\ValidationService;

class UserController extends ApiController{
    public function Get(Request $request){
        return $this->SendSuccessResponse(null,User::all());
    }
    public function GetById(Request $request){
        return $this->SendSuccessResponse(null,User::GetById($request->user_id));
    }
		
	public function GetUserByEmailAndOtherByFBID(Request $request){
		$objUserFacebook = User::GetByFacebookId($request["facebook_id"]);
		$objUserEmail = User::GetByEmail($request["email"]);
		
		return $this->SendSuccessResponse(null,array("user_facebook"=>$objUserFacebook,"user_email"=>$objUserEmail));
	}
    
    public function UpdateData(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationService::UserUpdate($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {

                $objUser = User::GetById($request['id']);
                $objUser->dni = $request['dni'];
                $objUser->first_name = $request['first_name'];
                $objUser->last_name = $request['last_name'];
                $objUser->phone = $request['phone'];
                $objUser->email = $request['email'];
                $objUser->save();
                if(intval($request['is_modify_address'])==1){
                    switch(intval($request['status'])){
                        case -1:
                            Address::DelByAdd($request['old_ub_id']);
                            ;break;
                        case 0:
                            if($request['new_ub_id']!="" && $request['new_ub_id']!=-1){
                                Address::UpByAdd('ubication_id',$request['new_ub_id'],$request['old_ub_id']);
                            }
                            if($request['new_add_st']!=""){
                                Address::UpByAdd('address',$request['new_add_st'],$request['old_ub_id']);
                            }
                            ;break;
                        case 1:
                            $objAdd = new Address();
                            $objAdd->user_id = $request['id'];
                            $objAdd->ubication_id = $request['new_ub_id'];
                            $objAdd->address = $request['new_add_st'];
                            $objAdd->phone = $request['add_phone'];
                            $objAdd->save();
                            ;break;
                        default: dd(intval($request['status']));
                    }
                }
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objUser));
            }
        } catch (\Exception $ex) {
            dd($ex);
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
        
    }

	public function GetByEmail(Request $request){
		$user = User::GetByEmail($request->email);
        return $this->SendSuccessResponse(null,$user);
    }
}

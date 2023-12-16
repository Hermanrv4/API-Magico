<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Contact;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\User;
use App\Http\Modules\Site\Services\ValidationContact;
use Illuminate\Http\Request;

class ContactController extends ApiController{
    public function Get(Request $request){
        //return $this->SendSuccessResponse(null,Cart::GetByUserId($request["user_id"]));
    }
    public function Add(Request $request){
		try{
			$msg_validation=null;
			$validator=ValidationContact::Register($request, $msg_validation);
			if($validator->fails()){
				return $this->SendErrorResponse(trans($msg_validation.'form.result.error'), $validator->errors());
			}
			$newcontact = new Contact();
			if(isset($request["name_company"])){
				if($request["is_company"]==1 && $request["name_company"]!="" && $request["name_company"]!=null){
					$newcontact->is_company = $request["is_company"];
					$newcontact->name_company = $request["name_company"];
				}
			}
			$newcontact->email = $request["email"];
			$newcontact->first_name = $request["name"];
			$newcontact->last_name = $request["last_name"];
			$newcontact->phone = $request["phone"];
			$newcontact->message = $request["message"];
			$newcontact->save();
			
			return $this->SendSuccessResponse(null,$newcontact);
		}catch(\Exception $ex){

		}
			/* if(strpos($request["email"],"@")==false || $request["name"]=="" || $request["name"]==null || 
			$request["last_name"]=="" || $request["last_name"]==null || $request["phone"]=="" || $request["phone"]==null){
				return $this->SendErrorResponse(null,[$request['name']]);
			}else{ */
			/* } */
        //return $this->SendSuccessResponse(null,Cart::GetByUserId($request["user_id"]));
    }
}

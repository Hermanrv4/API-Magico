<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Models\Database\Contact;
use App\Http\Models\Database\Parameter;
use App\Http\Modules\Admin\Services\ValidationContact;

class ContactController extends ApiController{
    
    public function Get(Request $request){
        if (isset($request["contact_id"])) {
            return $this->SendSuccessResponse(null,Contact::GetById($request["contact_id"]));
        }else{
            return $this->SendSuccessResponse(null,Contact::all());
        }
    }

    public function Register(Request $request){        
        try {
            $msg_validation=null;
            $validator = ValidationContact::ContactRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objContact = new Contact();
                $is_update = $request['id']!=Parameter::GetByCode('default_id');
                if($is_update) $objContact = Contact::GetById($request['id']);
                $objContact->email = $request['email'];
                $objContact->first_name = $request['first_name'];
                $objContact->last_name = $request['last_name'];
                $objContact->phone = $request['phone'];
                $objContact->message = $request['message'];
                $objContact->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objContact));
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
            $validator = ValidationContact::ContactDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                	$objContact = Contact::GetById($request['id']);
                    $objContact->delete();
                    return $this->SendSuccessResponse(null,array('result'=>$objContact));
            }
            
        } catch (\Throwable $th) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}
<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\EventInvitation;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationEventInvitation;

class EventInvitationController extends ApiController{

    public function Get(Request $request){
        if (isset($request["event_invitation_id"])) {
            return $this->SendSuccessResponse(null,EventInvitation::GetById($request["event_invitation_id"])->first());
        }
        if (isset($request["event_id"])) {
            return $this->SendSuccessResponse(null,EventInvitation::GetByEventId($request["event_id"]));
        }
        else{
            return $this->SendSuccessResponse(null,EventInvitation::all());
        }
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationEventInvitation::EventInvitationRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objEventInvitation = new EventInvitation();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objEventInvitation = EventInvitation::GetById($request['id']);
                $objEventInvitation->event_id = $request['event_id'];
                $objEventInvitation->email = $request['email'];
                $objEventInvitation->full_name = $request['full_name'];
                $objEventInvitation->is_original = $request['is_original'];
                $objEventInvitation->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objEventInvitation));
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
            $validator = ValidationEventInvitation::EventInvitationDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objEventInvitation = EventInvitation::GetById($request['id'])->first();
                $objEventInvitation->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objEventInvitation));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse($ex->getMessage());
        }
    }
}

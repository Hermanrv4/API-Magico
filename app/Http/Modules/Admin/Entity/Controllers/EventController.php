<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use Carbon\Carbon;
use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Event;
use App\Http\Models\Database\EventInvitation;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationEvent;

class EventController extends ApiController{

    public function Get(Request $request){
        if(isset($request['id_user']) && $request['id_user']!="" || $request['id_user']!=null){
			return $this->SendSuccessResponse(null, Event::where('user_id', "=", $request['id_user'])->get());
		}
        else if (isset($request["event_id"])) {
            return $this->SendSuccessResponse(null,Event::GetById($request["event_id"]));
        }else{
            return $this->SendSuccessResponse(null,Event::all());
        }
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationEvent::EventRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objEvent = new Event();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objEvent = Event::GetById($request['id']);
                $objEvent->user_id = $request['user_id'];
                $objEvent->name = $request['name'];
                $objEvent->description = $request['description'];
                $objEvent->end_at = $request['end_at'];
                $objEvent->end_at = $request['address_id'];
                $objEvent->end_at = $request['gratitude'];
                $objEvent->end_at = $request['start_event'];
                $objEvent->end_at = $request['address'];
                $objEvent->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objEvent));
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
            $validator = ValidationEvent::EventDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objEvent = Event::GetById($request['id']);
                $objEvent->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objEvent));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
    public function GetDateEvents(Request $request){
        return $this->SendSuccessResponse(null, Event::GetEventsDate($request['date_end']));
    }
    
    public function GetNoSentReminderWithGuests(){

        try {
        
            $eventsBeforeStart = Event::GetNoSentReminder();
            $invitationsBeforeStart = EventInvitation::GetNoSentReminderEvents();
            $days_allowed = Parameter::GetByCodeValue("days_allowed");

            $today = Carbon::parse(date("Ymd"));
            
            $currentEvents = array();
            
            foreach($eventsBeforeStart as $rowEvent) {    
                
                $dateStart = Carbon::parse($rowEvent->start_event);
                $diffDays = $dateStart->diffInDays($today);

                $currentInvitations = array();
                
                foreach($invitationsBeforeStart as $rowInvitation){
                    if($rowEvent->id === $rowInvitation->event_id){
                        array_push($currentInvitations, $rowInvitation);
                    }
                }
                $rowEvent->invitations = $currentInvitations;

                if(strval($diffDays) === $days_allowed->value){
                    array_push($currentEvents, $rowEvent);
                }
            }
            
            return $this->SendSuccessResponse(null, $currentEvents);
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function UpdateSentReminderSuccess(Request $request){
        try {

            Event::UpdateSentReminder($request['event_id']);
        
            /* $eventsUpdated = Event::UpdateSentReminder(); */
            
            return $this->SendSuccessResponse(null, array('result' => true));
            
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}

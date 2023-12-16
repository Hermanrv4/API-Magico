<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationEventInvitation{

    public static function EventInvitationRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.event_invitation').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('event_id'=>['required','exists:events,id']);
        $rules += array('email'=>['required','email','between:3,150']);
        $rules += array('full_name'=>['nullable','between:3,100']);
        $rules += array('is_original'=>['boolean']);
        $messages=array();
        $messages += array("event_id.exists"=>trans($msg_validation.'event_id.exists'));
        $messages += array("event_id.required"=>trans($msg_validation.'event_id.required'));
        $messages += array("email.required"=>trans($msg_validation.'email.required'));
        $messages += array("email.between"=>trans($msg_validation.'email.between'));
        $messages += array("full_name.between"=>trans($msg_validation.'name.between'));
        $messages += array("is_original.boolean"=>trans($msg_validation.'is_original.boolean'));
        return Validator::make($inputs,$rules,$messages);
    }
    
    public static function EventInvitationDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.event_invitation').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:event_invitations,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
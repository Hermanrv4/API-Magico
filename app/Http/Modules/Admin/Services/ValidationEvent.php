<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationEvent{

    public static function EventRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.event').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('user_id'=>['nullable','exists:users,id']);
        $rules += array('name'=>['required','max:150']);
        $rules += array('description'=>['max:500']);
        $messages=array();
        $messages += array("user_id.exists"=>trans($msg_validation.'user_id.exists'));
        $messages += array("name.required"=>trans($msg_validation.'name.required'));
        $messages += array("name.max"=>trans($msg_validation.'name.max'));
        $messages += array("description.max"=>trans($msg_validation.'description.max'));
        return Validator::make($inputs,$rules,$messages);
    }
    
    public static function EventDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.event').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:events,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
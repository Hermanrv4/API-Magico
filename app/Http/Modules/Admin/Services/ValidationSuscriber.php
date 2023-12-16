<?php
namespace App\Http\Modules\Admin\Services;
use Illuminate\Support\Facades\Validator;
use App\Http\Modules\Admin\Services\ValidationService;


class ValidationSuscriber{

    public static function SuscriberRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.suscriber').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('email'=>['required','email',ValidationService::BuildUniqueField('suscribers','email',$request['id'])]);
        $rules += array('info_suscriber'=>['max:200']);
        $messages=array();
        $messages += array("email.required"=>trans($msg_validation.'email.required'));
        $messages += array("email.email"=>trans($msg_validation.'email.email'));
        $messages += array("email.unique"=>trans($msg_validation.'email.unique'));
        $messages += array("info_susriber.max"=>trans($msg_validation.'info_susriber.max'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function SuscriberDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.suscriber').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:suscribers,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
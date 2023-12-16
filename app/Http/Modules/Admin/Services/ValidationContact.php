<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationContact{

    public static function ContactRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.contact').'register.';
        $inputs = $request->all();
        $rules = array();
        //falta validar bien los datos
        $rules += array('email'=>['required','email']);
        $rules += array('first_name'=>['required','string','between:2,100']);
        $rules += array('last_name'=>['required','string','between:2,100']);
        $rules += array('phone'=>['required','digits_between:7,9']);
        $rules += array('message'=>['required']);
        
        $messages=array();
        $messages += array("email.required"=>trans($msg_validation.'email.required'));
        $messages += array("email.email"=>trans($msg_validation.'email.email'));
        $messages += array("first_name.required"=>trans($msg_validation.'first_name.required'));
        $messages += array("first_name.string"=>trans($msg_validation.'first_name.string'));
        $messages += array("first_name.between"=>trans($msg_validation.'first_name.between'));
        $messages += array("last_name.required"=>trans($msg_validation.'last_name.required'));
        $messages += array("last_name.string"=>trans($msg_validation.'last_name.string'));
        $messages += array("last_name.between"=>trans($msg_validation.'last_name.between'));
        $messages += array("phone.required"=>trans($msg_validation.'phone.required'));
        $messages += array("phone.digits_between"=>trans($msg_validation.'phone.digits_between'));
        $messages += array("message.required"=>trans($msg_validation.'message.required'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function ContactDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.contact').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:contacts,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
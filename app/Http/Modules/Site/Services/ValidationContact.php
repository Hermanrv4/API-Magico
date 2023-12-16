<?php
namespace App\Http\Modules\Site\Services;

use App\Http\Models\Database;
/* use App\Http\Models\Database\User; */
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidationContact{
    public static function Register($request, &$msg_validation=null){
        $msg_validation=config('site.lang.validation.contact').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('name'=>['required', 'string']);
        $rules += array('email'=>['required', 'email', 'string']);
        $rules += array('last_name'=>['required', 'string']);
        $rules += array('phone'=>['required', 'numeric']);
        $rules += array('message'=>['string']);
        $messages=array();
        $messages += array("name.required"=>trans($msg_validation.'name.required'));
        $messages += array("name.string"=>trans($msg_validation.'name.string'));
        $messages += array("email.required"=>trans($msg_validation.'email.required'));
        $messages += array("email.email"=>trans($msg_validation.'email.email'));
        $messages += array("email.string"=>trans($msg_validation.'name.string'));
        $messages += array("last_name.required"=>trans($msg_validation.'last_name.required'));
        $messages += array("last_name.string"=>trans($msg_validation.'last_name.string'));
        $messages += array("phone.required"=>trans($msg_validation.'phone.required'));
        $messages += array("phone.numeric"=>trans($msg_validation.'phone.numeric'));
        $messages += array("message.string"=>trans($msg_validation.'message.string'));
        return Validator::make($inputs, $rules, $messages);
    }
}
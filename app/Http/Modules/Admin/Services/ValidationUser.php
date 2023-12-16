<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationUser{

    public static function UserRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.user').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('dni'=>['nullable','digits_between:8,10',ValidationService::BuildUniqueField('users','dni',$request['id'])]);
        $rules += array('first_name'=>['required','between:2,50']);
        $rules += array('last_name'=>['required','between:2,50']);
        $rules += array('phone'=>['nullable','digits_between:7,10']);
        $rules += array('email'=>['required',ValidationService::BuildUniqueField('users','email',$request['id'])]);
        
        if($request["facebook_id"]!=null) {
            $rules += array('facebook_id' => ['required']);
        }else{
            if ($request['id']!=Parameter::GetByCode('default_id')) {
                $rules += array('password' => ['nullable','between:8,20']);                
            }
            $rules += array('password' => ['required', 'between:8,20']);
        }
        $rules += array('is_admin'=>['boolean']);
        $messages=array();
        $messages += array("dni.digits_between"=>trans($msg_validation.'dni.digits_between'));
        $messages += array("dni.unique"=>trans($msg_validation.'dni.unique'));
        $messages += array("first_name.required"=>trans($msg_validation.'first_name.required'));
        $messages += array("first_name.between"=>trans($msg_validation.'first_name.between'));
        $messages += array("last_name.required"=>trans($msg_validation.'last_name.required'));
        $messages += array("last_name.between"=>trans($msg_validation.'last_name.between'));
        $messages += array("phone.nullable"=>trans($msg_validation.'phone.nullable'));
        $messages += array("phone.digits_between"=>trans($msg_validation.'phone.digits_between'));
        $messages += array("email.required"=>trans($msg_validation.'email.required'));
        $messages += array("email.unique"=>trans($msg_validation.'email.unique'));
        $messages += array("facebook_id.required"=>trans($msg_validation.'facebook_id.required'));
        $messages += array("password.required"=>trans($msg_validation.'password.required'));
        $messages += array("password.between"=>trans($msg_validation.'password.between'));
        return Validator::make($inputs,$rules,$messages);
    }
    
    public static function UserDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.user').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:users,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
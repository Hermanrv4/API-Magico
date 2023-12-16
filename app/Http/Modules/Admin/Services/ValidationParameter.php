<?php
namespace App\Http\Modules\Admin\Services;
use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationParameter{
    public static function ParameterRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.parameter').'register.';
        $inputs = $request->all();
        $rules = array();        
        $rules += array('value'=>['required']);
        $messages=array();       
        $messages += array("value.required"=>trans($msg_validation.'value.required'));
        return Validator::make($inputs,$rules,$messages);
    }
}
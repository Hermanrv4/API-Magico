<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationType{

    public static function TypeRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.type').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('type_group_id'=>['required','exists:type_groups,id']);
        $rules += array('code'=>['required','between:3,50',ValidationService::BuildUniqueField('types','code',$request['id'])]);
        $rules += array('name'=>['required','between:3,70',ValidationService::BuildUniqueField('types','name',$request['id'])]);
        $messages=array();
        $messages += array("type_group_id.required"=>trans($msg_validation.'type_group_id.required'));
        $messages += array("type_group_id.exists"=>trans($msg_validation.'type_group_id.exists'));
        $messages += array("code.required"=>trans($msg_validation.'code.required'));
        $messages += array("code.between"=>trans($msg_validation.'code.between'));
        $messages += array("code.unique"=>trans($msg_validation.'code.unique'));
        $messages += array("name.required"=>trans($msg_validation.'name.required'));
        $messages += array("name.unique"=>trans($msg_validation.'name.unique'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function TypeDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.type').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:types,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
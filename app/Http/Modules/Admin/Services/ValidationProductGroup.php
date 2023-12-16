<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationProductGroup{

    public static function ProductGroupRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.product_group').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('category_id'=>['required','exists:categories,id']);
        $rules += array('code'=>['required','between:1,50',ValidationService::BuildUniqueField('product_groups','code',$request['id'])]);
        $rules += array('name'=>['required',ValidationService::BuildUniqueField('product_groups','name',$request['id'])]);
        $messages=array();
        $messages += array("category_id.required"=>trans($msg_validation.'category_id.required'));
        $messages += array("category_id.exists"=>trans($msg_validation.'category_id.exists'));
        $messages += array("code.required"=>trans($msg_validation.'code.required'));
        $messages += array("code.between"=>trans($msg_validation.'code.between'));
        $messages += array("code.unique"=>trans($msg_validation.'code.unique'));
        $messages += array("name.required"=>trans($msg_validation.'name.required'));
        $messages += array("name.unique"=>trans($msg_validation.'name.unique'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function ProductGroupDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.product_group').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:product_groups,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
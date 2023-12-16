<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationCategory{

    public static function CategoryRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.category').'register.';
        $inputs = $request->all();
        $rules = array();
        if ($request['root_category_id']!=Parameter::GetByCode('default_id')) {
            $rules += array('root_category_id'=>['required','exists:categories,id',Rule::notIn([$request['id']])]);
        }
        $rules += array('url_code'=>['required',ValidationService::BuildUniqueField('categories','url_code',$request['id'])]);
        $rules += array('code'=>['required','between:1,50',ValidationService::BuildUniqueField('categories','code',$request['id'])]);
        $rules += array('name'=>['required','between:1,70',ValidationService::BuildUniqueField('categories','name',$request['id'])]);
        $messages=array();
        $messages += array("root_category_id.required"=>trans($msg_validation.'root_category_id.required'));
        $messages += array("root_category_id.exists"=>trans($msg_validation.'root_category_id.exists'));
        $messages += array("root_category_id.not_in"=>trans($msg_validation.'root_category_id.not_in'));
        $messages += array('url_code.required'=>trans($msg_validation.'url_code.required'));
        $messages += array('url_code.unique'=>trans($msg_validation.'url_code.unique'));
        $messages += array("code.required"=>trans($msg_validation.'code.required'));
        $messages += array("code.between"=>trans($msg_validation.'code.between'));
        $messages += array("code.unique"=>trans($msg_validation.'code.unique'));
        $messages += array("name.required"=>trans($msg_validation.'name.required'));
        $messages += array("name.between"=>trans($msg_validation.'name.between'));
        $messages += array("name.unique"=>trans($msg_validation.'name.unique'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function CategoryDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.category').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:categories,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
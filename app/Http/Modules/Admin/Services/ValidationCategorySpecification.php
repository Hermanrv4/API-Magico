<?php
namespace App\Http\Modules\Admin\Services;
use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationCategorySpecification{
    public static function CategorySpecificationRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.category_specification').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('category_id'=>['required','exists:categories,id']);
        $rules += array('specification_id'=>['nullable','exists:specifications,id']);
        $rules += array('is_filter'=>['boolean']);
        $messages=array();
        $messages += array("category_id.required"=>trans($msg_validation.'category_id.required'));
        $messages += array("category_id.exists"=>trans($msg_validation.'category_id.exists'));
        $messages += array("specification_id.exists"=>trans($msg_validation.'specification_id.exists'));
        $messages += array("is_filter.boolean"=>trans($msg_validation.'is_filter.boolean'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function CategorySpecificationDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.category_specification').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:category_specifications,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }

}
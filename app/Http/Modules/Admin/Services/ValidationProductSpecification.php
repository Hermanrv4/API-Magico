<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Models\Database\ProductSpecification;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationProductSpecification{

    public static function ProductSpecificationRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.product_specification').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('product_id'=>['required','exists:products,id']);
        $rules += array('specification_id'=>['required','exists:specifications,id',ValidationService::BuildUniqueTwoFields('product_specifications','specification_id','product_id',$request['product_id'],$request['id'])]);
        $rules += array('value'=>['required','max:500']);
        $messages=array();
        $messages += array("product_id.required"=>trans($msg_validation.'product_id.required'));
        $messages += array("product_id.exists"=>trans($msg_validation.'product_id.exists'));        
        $messages += array("specification_id.required"=>trans($msg_validation.'specification_id.required'));
        $messages += array("specification_id.exists"=>trans($msg_validation.'specification_id.exists'));
        $messages += array("specification_id.unique"=>trans($msg_validation.'specification_id.unique'));
        $messages += array("value.required"=>trans($msg_validation.'value.required'));
        $messages += array("value.max"=>trans($msg_validation.'value.max'));
        return Validator::make($inputs,$rules,$messages);
    }

    public static function ProductSpecificationDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.product_specification').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:product_specifications,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
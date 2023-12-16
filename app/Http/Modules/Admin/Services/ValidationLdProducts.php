<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
/* use Illuminate\Http\Request; */
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationLdProducts{
    public static function LdProductsRegister($request, & $msg_validation=null){
        $msg_validation= config('admin.lang.validation.ldproducts').'register.';
        $inputs = $request;
        $rules = array();
        $rules += array('category_code'=>['required']);
        $rules += array('especifications'=>['required']);
        $rules += array('product_group'=>['required']);
        $messages=array();
        $messages += array('category_code.required'=>trans($msg_validation.'category_code.required'));
        $messages += array('especifications.required'=>trans($msg_validation.'especifications.required'));
        $messages += array('product_group.required'=>trans($msg_validation.'product_group.required')); 
        return Validator::make($inputs, $rules, $messages);
    }
}
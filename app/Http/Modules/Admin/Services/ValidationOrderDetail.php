<?php
namespace App\Http\Modules\Admin\Services;
use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationOrderDetail{
    public static function OrderDetailRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.order_detail').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('order_id'=>['required','exists:orders,id']);
        $rules += array('product_id'=>['required','exists:products,id']);
        $rules += array('quantity'=>['required','numeric']);
        $rules += array('price'=>['required','numeric']);
        //$rules += array('observations'=>['nullable']);
        $messages=array();
        $messages += array("order_id.required"=>trans($msg_validation.'order_id.required'));
        $messages += array("order_id.exists"=>trans($msg_validation.'order_id.exists'));
        $messages += array("product_id.required"=>trans($msg_validation.'product_id.required'));
        $messages += array("product_id.exists"=>trans($msg_validation.'product_id.exists'));
        $messages += array("quantity.required"=>trans($msg_validation.'quantity.required'));
        $messages += array("quantity.numeric"=>trans($msg_validation.'quantity.numeric'));
        $messages += array("price.required"=>trans($msg_validation.'price.required'));
        $messages += array("price.numeric"=>trans($msg_validation.'price.numeric'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function OrderDetailDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.order_detail').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:order_details,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
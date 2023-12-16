<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationListProductsEvent{

    public static function ListProductsEventRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.list_products_event').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('event_id'=>['required','exists:events,id']);
        $rules += array('product_id'=>['required','exists:products,id']);
        $rules += array('quantity'=>['numeric']);
        $rules += array('quantity_acumulated'=>['numeric']);
        $messages=array();
        $messages += array("event_id.exists"=>trans($msg_validation.'event_id.exists'));
        $messages += array("event_id.required"=>trans($msg_validation.'event_id.required'));
        $messages += array("product_id.exists"=>trans($msg_validation.'product_id.exists'));
        $messages += array("product_id.required"=>trans($msg_validation.'product_id.required'));
        $messages += array("quantity.numeric"=>trans($msg_validation.'quantity.numeric'));
        $messages += array("quantity_acumulated.numeric"=>trans($msg_validation.'quantity_acumulated.numeric'));
        return Validator::make($inputs,$rules,$messages);
    }
    
    public static function ListProductsEventDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.list_products_event').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:list_products_events,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
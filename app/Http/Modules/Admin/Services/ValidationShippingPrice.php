<?php
namespace App\Http\Modules\Admin\Services;
use Illuminate\Support\Facades\Validator;

class ValidationShippingPrice{

    public static function ShippingPriceRegister($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.shipping_price').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('ubication_id'=>['required','exists:ubications,id']);
        $rules += array('currency_id'=>['required','exists:currencies,id',ValidationService::BuildUniqueTwoFields('shipping_prices','currency_id','ubication_id',$request['ubication_id'],$request['id'])]);
        $rules += array('price'=>['required','numeric']);
        $rules += array('min_days'=>['required','integer']);
        $rules += array('is_static'=>['required','boolean']);
        $messages = array();
        $messages += array('ubication_id.required'=>trans($msg_validation.'ubication_id.required'));
        $messages += array('ubication_id.exists'=>trans($msg_validation.'ubication_id.exists'));
        $messages += array('currency_id.required'=>trans($msg_validation.'currency_id.required'));
        $messages += array('currency_id.exists'=>trans($msg_validation.'currency_id.exists'));
        $messages += array('currency_id.unique'=>trans($msg_validation.'currency_id.unique'));
        $messages += array('price.required'=>trans($msg_validation.'price.required'));
        $messages += array('price.numeric'=>trans($msg_validation.'price.numeric'));
        $messages += array('min_days.required'=>trans($msg_validation.'min_days.required'));
        $messages += array('min_days.integer'=>trans($msg_validation.'min_days.integer'));
        $messages += array('is_static.required'=>trans($msg_validation.'is_static.required'));
        $messages += array('is_static.boolean'=>trans($msg_validation.'is_static.boolean'));
        return Validator::make($inputs,$rules,$messages);
    }

    public static function ShippingPriceUpdate($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.shipping_price').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('ubication_id'=>['required','exists:ubications,id']);
        $rules += array('currency_id'=>['required','exists:currencies,id']);
        $rules += array('price'=>['required','numeric']);
        $rules += array('min_days'=>['required','integer']);
        $rules += array('is_static'=>['required','boolean']);
        $messages = array();
        $messages += array('ubication_id.required'=>trans($msg_validation.'ubication_id.required'));
        $messages += array('ubication_id.exists'=>trans($msg_validation.'ubication_id.exists'));
        $messages += array('currency_id.required'=>trans($msg_validation.'currency_id.required'));
        $messages += array('currency_id.exists'=>trans($msg_validation.'currency_id.exists'));
        $messages += array('price.required'=>trans($msg_validation.'price.required'));
        $messages += array('price.numeric'=>trans($msg_validation.'price.numeric'));
        $messages += array('min_days.required'=>trans($msg_validation.'min_days.required'));
        $messages += array('min_days.integer'=>trans($msg_validation.'min_days.integer'));
        $messages += array('is_static.required'=>trans($msg_validation.'is_static.required'));
        $messages += array('is_static.boolean'=>trans($msg_validation.'is_static.boolean'));
        return Validator::make($inputs,$rules,$messages);
    }

    public static function ShippingPriceDelete($request,&$msg_validation=null)
    {
        $msg_validation = config('admin.lang.validation.shipping_price').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:shipping_prices,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);

    }

}
<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationProductPrice{

    public static function ProductPriceRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.product_price').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('product_id'=>['required','exists:products,id']);
        $rules += array('currency_id'=>['required','exists:currencies,id',ValidationService::BuildUniqueTwoFields('product_prices','currency_id','product_id',$request['ubication_id'],$request['id'])]);
        $rules += array('regular_price'=>['nullable','numeric']);
        $rules += array('online_price'=>['required','numeric']);
        $messages=array();
        $messages += array("product_id.required"=>trans($msg_validation.'product_id.required'));
        $messages += array("product_id.exists"=>trans($msg_validation.'product_id.exists'));        
        $messages += array("currency_id.required"=>trans($msg_validation.'currency_id.required'));
        $messages += array("currency_id.exists"=>trans($msg_validation.'currency_id.exists'));
        $messages += array("currency_id.unique"=>trans($msg_validation.'currency_id.unique'));
        $messages += array("regular_price.numeric"=>trans($msg_validation.'regular_price.numeric'));
        $messages += array("online_price.required"=>trans($msg_validation.'online_price.required'));
        $messages += array("online_price.numeric"=>trans($msg_validation.'online_price.numeric'));
        return Validator::make($inputs,$rules,$messages);
    }

    public static function ProductPriceDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.product_price').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:product_prices,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
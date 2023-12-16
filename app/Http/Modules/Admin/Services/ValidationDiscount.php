<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationDiscount{
    public static function DiscountRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.discount').'.register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('name'=>['required']);
        $rules += array('id_type_discounts'=>['required']);
        $rules += array('code'=>['required', ValidationService::BuildUniqueField('discounts', 'code', $request['coupon_id'])]);
        $rules += array('description'=>['required']);
        $rules += array('id_type_discounts'=>['required', 'exists:types,id']);
        $rules += array('value'=>['required']);
        $rules += array('date_end'=>['required']);
        $rules += array('date_start'=>['required']);
        $messages=array();
        $messages += array("name.required"=>trans($msg_validation.'name.required'));
        $messages += array("code.required"=>trans($msg_validation.'code.required'));
        $messages += array("code.unique"=>trans($msg_validation.'code.unique'));
        $messages += array('id_type_discounts.required'=>trans($msg_validation.'id_type_discounts.required'));
        $messages += array('id_type_discounts.exists'=>trans($msg_validation.'id_type_discounts.exists'));
        $messages += array("description.required"=>trans($msg_validation.'description.required'));
        $messages += array("value.required"=>trans($msg_validation.'value.required'));
        $messages += array("date_start.required"=>trans($msg_validation.'date_start.required'));
        $messages += array("date_end.required"=>trans($msg_validation.'date_end.required'));
        return Validator::make($inputs,$rules,$messages);
    }
    /* public static function ElectronicBillingSaleVoided($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.electronic_billing_sale').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('order_id'=>['required','exists:orders,id','exists:electronic_billing_sales,order_id']);
        $messages=array();
        $messages += array("order_id.required"=>trans($msg_validation.'order_id.required'));
        $messages += array("order_id.exists"=>trans($msg_validation.'order_id.exists'));
        return Validator::make($inputs,$rules,$messages);
    } */
}
<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationClaimBook{

    public static function ClaimBookRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.claim_book').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('type'=>['required']);
        $rules += array('correlative'=>['required']);
        $rules += array('customer_name'=>['required']);
        $rules += array('customer_dni'=>['required']);
        $rules += array('customer_address'=>['required']);
        $rules += array('customer_email'=>['required']);
        $rules += array('customer_phone'=>['required']);
        $rules += array('customer_younger'=>['required']);
        $rules += array('flg_younger'=>['boolean']);
        $rules += array('parent_name'=>['nullable']);
        $rules += array('parent_dni'=>['nullable']);
        $rules += array('parent_address'=>['nullable']);
        $rules += array('parent_email'=>['nullable']);
        $rules += array('parent_phone'=>['nullable']);
        $rules += array('status'=>['required']);
        $rules += array('detail_product'=>['required']);
        $rules += array('detail'=>['required']);
        $rules += array('detail_answer'=>['required']);
        $rules += array('date_register'=>['required']);
        $rules += array('date_answer'=>['nullable']);
        $messages=array();
        $messages += array("type.required"=>trans($msg_validation.'type.required'));
        $messages += array("correlative.required"=>trans($msg_validation.'correlative.required'));
        $messages += array("customer_name.required"=>trans($msg_validation.'customer_name.required'));
        $messages += array("customer_dni.required"=>trans($msg_validation.'customer_dni.required'));
        $messages += array("customer_address.required"=>trans($msg_validation.'customer_address.required'));
        $messages += array("customer_email.required"=>trans($msg_validation.'customer_email.required'));
        $messages += array("customer_phone.required"=>trans($msg_validation.'customer_phone.required'));
        $messages += array("customer_younger.required"=>trans($msg_validation.'customer_younger.required'));
        $messages += array("flg_younger.boolean"=>trans($msg_validation.'flg_younger.boolean'));
        $messages += array("status.required"=>trans($msg_validation.'status.required'));
        $messages += array("detail_product.required"=>trans($msg_validation.'detail_product.required'));
        $messages += array("detail.required"=>trans($msg_validation.'detail.required'));
        $messages += array("detail_answer.required"=>trans($msg_validation.'detail_answer.required'));
        $messages += array("date_register.required"=>trans($msg_validation.'date_register.required'));
        $messages += array("date_answer.required"=>trans($msg_validation.'date_answer.required'));
        return Validator::make($inputs,$rules,$messages);
    }
}
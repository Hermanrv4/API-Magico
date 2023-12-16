<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationElectronicBillingSale{
    public static function ElectronicBillingSaleRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.electronic_billing_sale').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('serie'=>['required','between:4,23',ValidationService::BuildUniqueTwoFields('electronic_billing_sales','serie','correlative',$request['correlative'])]);
        $rules += array('correlative'=>['required','between:5,8']);
        $rules += array('order_id'=>['required','exists:orders,id']);
        $rules += array('status'=>['size:1']);
        $messages=array();
        $messages += array("serie.required"=>trans($msg_validation.'serie.required'));
        $messages += array("serie.between"=>trans($msg_validation.'serie.between'));
        $messages += array("serie.unique"=>trans($msg_validation.'serie.unique'));
        $messages += array("correlative.required"=>trans($msg_validation.'correlative.required'));
        $messages += array("correlative.between"=>trans($msg_validation.'correlative.between'));
        $messages += array("order_id.required"=>trans($msg_validation.'order_id.required'));
        $messages += array("order_id.exists"=>trans($msg_validation.'order_id.exists'));
        $messages += array("status.size"=>trans($msg_validation.'status.size'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function ElectronicBillingSaleVoided($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.electronic_billing_sale').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('order_id'=>['required','exists:orders,id','exists:electronic_billing_sales,order_id']);
        $messages=array();
        $messages += array("order_id.required"=>trans($msg_validation.'order_id.required'));
        $messages += array("order_id.exists"=>trans($msg_validation.'order_id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
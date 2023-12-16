<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationProduct{

    public static function ProductRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.product').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('product_group_id'=>['required','exists:product_groups,id']);
        $rules += array('sku'=>['required',ValidationService::BuildUniqueField('products','sku',$request['id'])]);
        $rules += array('url_code'=>['required',ValidationService::BuildUniqueField('products','url_code',$request['id'])]);
        $rules += array('name'=>['required']);
        //$rules += array('description'=>[]);
        $rules += array('is_for_catalogue'=>['required','boolean']);
        $rules += array('is_active'=>['required','boolean']);
        $rules += array('stock'=>['required','numeric']);
        $rules += array('shipping_size'=>['required','numeric']);
        $messages=array();
        $messages += array("product_group_id.required"=>trans($msg_validation.'product_group_id.required'));
        $messages += array("product_group_id.exists"=>trans($msg_validation.'product_group_id.exists'));
        $messages += array("sku.required"=>trans($msg_validation.'sku.required'));
        $messages += array("sku.unique"=>trans($msg_validation.'sku.unique'));
        $messages += array("name.required"=>trans($msg_validation.'name.required'));
        $messages += array("name.unique"=>trans($msg_validation.'name.unique'));
        $messages += array("is_for_catalogue.required"=>trans($msg_validation.'is_for_catalogue.required'));
        $messages += array("is_for_catalogue.boolean"=>trans($msg_validation.'is_for_catalogue.boolean'));
        $messages += array("is_active.required"=>trans($msg_validation.'is_active.required'));
        $messages += array("is_active.boolean"=>trans($msg_validation.'is_active.boolean'));
        $messages += array("stock.required"=>trans($msg_validation.'stock.required'));
        $messages += array("stock.numeric"=>trans($msg_validation.'stock.numeric'));
        $messages += array("shipping_size.required"=>trans($msg_validation.'shipping_size.required'));
        $messages += array("shipping_size.numeric"=>trans($msg_validation.'shipping_size.numeric'));
        return Validator::make($inputs,$rules,$messages);
    }

    public static function ChangeStatus($request,&$msg_validation=null)
    {
        $msg_validation= config('admin.lang.validation.product').'change_status.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:products,id']);
        $rules += array('is_active'=>['boolean']);
        $messages=array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        $messages += array("is_active.boolean"=>trans($msg_validation.'is_active.boolean'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function ProductDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.product').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:products,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
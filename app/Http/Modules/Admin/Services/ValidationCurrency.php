<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationCurrency{

    public static function CurrencyRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.currency').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('code'=>['required','between:1,50',ValidationService::BuildUniqueField('currencies','code',$request['id'])]);
        $rules += array('symbol'=>['required','between:1,50',ValidationService::BuildUniqueField('currencies','symbol',$request['id'])]);
        $rules += array('name'=>['required','between:3,70',ValidationService::BuildUniqueField('currencies','name',$request['id'])]);
        $messages=array();
        $messages += array("code.required"=>trans($msg_validation.'code.required'));
        $messages += array("code.between"=>trans($msg_validation.'code.between'));
        $messages += array("code.unique"=>trans($msg_validation.'code.unique'));
        $messages += array("symbol.required"=>trans($msg_validation.'symbol.required'));
        $messages += array("symbol.between"=>trans($msg_validation.'symbol.between'));
        $messages += array("symbol.unique"=>trans($msg_validation.'symbol.unique'));
        $messages += array("name.required"=>trans($msg_validation.'name.required'));
        $messages += array("name.between"=>trans($msg_validation.'name.between'));
        $messages += array("name.unique"=>trans($msg_validation.'name.unique'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function CurrencyDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.currency').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:currencies,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
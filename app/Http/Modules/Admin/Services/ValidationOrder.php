<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationOrder{

    public static function ChangeStatus($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.order').'change_status.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:orders,id']);
        $rules += array('status_type_id'=>['required','exists:types,id']);
        $messages=array();
        $messages += array("type_id.exists"=>trans($msg_validation.'type_id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
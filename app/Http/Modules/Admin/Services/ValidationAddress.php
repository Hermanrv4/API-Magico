<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationAddress{

    public static function AddressRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.address').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('user_id'=>['required','exists:users,id']);
        $rules += array('wish_list_id'=>['nullable','exists:wish_lists,id']);
        $rules += array('ubication_id'=>['required','exists:ubications,id']);
        $rules += array('address'=>['required']);
        $messages=array();
        $messages += array("user_id.required"=>trans($msg_validation.'user_id.required'));
        $messages += array("user_id.exists"=>trans($msg_validation.'user_id.exists'));
        $messages += array("wish_list_id.exists"=>trans($msg_validation.'wish_list_id.exists'));
        $messages += array("ubication_id.required"=>trans($msg_validation.'ubication_id.required'));
        $messages += array("ubication_id.exists"=>trans($msg_validation.'ubication_id.exists'));
        $messages += array("address.required"=>trans($msg_validation.'address.required'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function AddressDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.address').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:addresses,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database;
use App\Http\Models\Database\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidationService{
    /******************************************************************************************************************/
    public static function BuildUniqueField($table,$column,$except_id=null){
        return Rule::unique($table,$column)->where(
            function ($query) use ($except_id)  {
                if($except_id!=null) $query->where('id','!=', intval($except_id));
            }
        );
    }
    public static function BuildUniqueTwoFields($table,$column,$column2,$column2_id,$except_id=NULL){
        return Rule::unique($table,$column)->where(
            function($query) use ($column2,$column2_id){
                return $query->where($column2,$column2_id);
            })->ignore($except_id,'id');
    }
    /******************************************************************************************************************/
    public static function UserLoginEmail($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.user').'login.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('email' => ['required','email','exists:users,email']);
        $rules += array('password' => ['required']);
        $messages = array();
        $messages += array("email.required" => trans($msg_validation.'email.required'));
        $messages += array("email.email" => trans($msg_validation.'email.email'));
        $messages += array("email.exists" => trans($msg_validation.'email.exists'));
        $messages += array("password.required" => trans($msg_validation.'password.required'));
        return Validator::make($inputs,$rules,$messages);
    }
    /******************************************************************************************************************/
}

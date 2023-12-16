<?php
namespace App\Http\Modules\Site\Services;

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
    /******************************************************************************************************************/
    public static function CustomerRegister($request,&$msg_validation=null){
        $msg_validation = config('site.lang.validation.user').'register.';

        $inputs = $request->all();
        $rules = array();
        $rules += array('dni' => [/*'required',*/'nullable','digits_between:8,10',ValidationService::BuildUniqueField('users','dni')]);
        $rules += array('first_name' => ['required','between:2,50']);
        $rules += array('last_name' => ['required','between:2,50']);
        $rules += array('phone' => [/*'required',*/'nullable','digits_between:7,10']);
        $rules += array('email' => ['required','email',ValidationService::BuildUniqueField('users','email')]);
        if($request["facebook_id"]!=null) {
            $rules += array('facebook_id' => ['required']);
        }else{
            $rules += array('password' => ['required', 'between:8,20']);
        }

        $messages = array();
       //$messages += array("dni.required" => trans($msg_validation.'dni.required'));
        $messages += array("dni.digits_between" => trans($msg_validation.'dni.digits_between'));
        $messages += array("dni.unique" => trans($msg_validation.'dni.unique'));
        $messages += array("first_name.required" => trans($msg_validation.'first_name.required'));
        $messages += array("first_name.between" => trans($msg_validation.'first_name.between'));
        $messages += array("last_name.required" => trans($msg_validation.'last_name.required'));
        $messages += array("last_name.between" => trans($msg_validation.'last_name.between'));
        //$messages += array("phone.required" => trans($msg_validation.'phone.required'));
        $messages += array("phone.digits_between" => trans($msg_validation.'phone.digits_between'));
        $messages += array("email.required" => trans($msg_validation.'email.required'));
        $messages += array("email.email" => trans($msg_validation.'email.email'));
        $messages += array("email.unique" => trans($msg_validation.'email.unique'));
        if($request["facebook_id"]!=null) {
            $messages += array("facebook_id.required" => trans($msg_validation . 'facebook_id.required'));
        }else{
            $messages += array("password.required" => trans($msg_validation . 'password.required'));
            $messages += array("password.between" => trans($msg_validation . 'password.between'));
        }

        return Validator::make($inputs,$rules,$messages);
    }
    public static function CustomerOnlyEmailLogin($request,&$msg_validation=null){
        $msg_validation = config('site.lang.validation.user').'register.';

        $inputs = $request->all();
        $rules = array();
        $messages = array();
       //$messages += array("dni.required" => trans($msg_validation.'dni.required'));
        $messages += array("email.required" => trans($msg_validation.'email.required'));
        $messages += array("email.email" => trans($msg_validation.'email.email'));  

        return Validator::make($inputs,$rules,$messages);
    }
    public static function CustomerEmailLogin($request,&$msg_validation=null){
        $msg_validation = config('site.lang.validation.user').'login.';
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
    public static function OrderRegister($request,&$msg_validation=null){
        $msg_validation = config('site.lang.validation.order').'register.';

        $inputs = $request->all();
        $rules = array();
        $rules += array('receiver_first_name' => ['required','between:2,50']);
        $rules += array('receiver_last_name' => ['required','between:2,50']);
        $rules += array('receiver_phone' => ['required','nullable','digits_between:7,10']);
        $rules += array('receiver_email' => ['required','email']);
        $rules += array('receiver_dni' => ['required','digits_between:8,10']);
        $rules += array('ubication_id' => ['required','exists:ubications,id']);
        $rules += array('address' => ['required','between:5,100']);
        $rules += array('token' => ['required',ValidationService::BuildUniqueField('orders','token')]);
        $rules += array('currency_code' => ['required','exists:currencies,code']);

        $messages = array();
        $messages += array("receiver_first_name.required" => trans($msg_validation.'receiver_first_name.required'));
        $messages += array("receiver_first_name.between" => trans($msg_validation.'receiver_first_name.between'));
        $messages += array("receiver_last_name.required" => trans($msg_validation.'receiver_last_name.required'));
        $messages += array("receiver_last_name.between" => trans($msg_validation.'receiver_last_name.between'));
        $messages += array("receiver_phone.required" => trans($msg_validation.'receiver_phone.required'));
        $messages += array("receiver_phone.digits_between" => trans($msg_validation.'receiver_phone.digits_between'));
        $messages += array("receiver_email.required" => trans($msg_validation.'receiver_email.required'));
        $messages += array("receiver_email.email" => trans($msg_validation.'receiver_email.email'));
        $messages += array("receiver_dni.required" => trans($msg_validation.'receiver_dni.required'));
        $messages += array("receiver_dni.digits_between" => trans($msg_validation.'receiver_dni.digits_between'));
        $messages += array("ubication_id.required" => trans($msg_validation.'ubication_id.required'));
        $messages += array("ubication_id.exists" => trans($msg_validation.'ubication_id.exists'));
        $messages += array("address.required" => trans($msg_validation.'address.required'));
        $messages += array("address.between" => trans($msg_validation.'address.between'));
        $messages += array("token.required" => trans($msg_validation.'token.required'));
        $messages += array("token.unique" => trans($msg_validation.'token.unique'));
        $messages += array("currency_code.required" => trans($msg_validation.'currency_code.required'));
        $messages += array("currency_code.exists" => trans($msg_validation.'currency_code.exists'));

        return Validator::make($inputs,$rules,$messages);
    }
    /******************************************************************************************************************/

	public static function EventRegister($request,&$msg_validation=null){
		$msg_validation = config('site.lang.validation.order').'register.';

        $inputs = $request->all();
        $rules = array();
        $rules += array('user_id' => ['required','exists:users,id']);
        $rules += array('name' => ['required','between:2,50']);
        $rules += array('description' => ['required','between:2,50']);
        $rules += array('ubication_id' => ['required','exists:ubications,id']);
        $rules += array('address_id' => ['required']);
        $rules += array('currency_code' => ['required','exists:currencies,code']);
	}

    public static function UserUpdate($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.user').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('dni'=>['nullable','digits_between:8,10',ValidationService::BuildUniqueField('users','dni',$request['id'])]);
        $rules += array('first_name'=>['required','between:2,50']);
        $rules += array('last_name'=>['required','between:2,50']);
        $rules += array('phone'=>['nullable','digits_between:7,10']);
        $rules += array('email'=>['required',ValidationService::BuildUniqueField('users','email',$request['id'])]);
        
        $messages=array();
        $messages += array("dni.digits_between"=>trans($msg_validation.'dni.digits_between'));
        $messages += array("dni.unique"=>trans($msg_validation.'dni.unique'));
        $messages += array("first_name.required"=>trans($msg_validation.'first_name.required'));
        $messages += array("first_name.between"=>trans($msg_validation.'first_name.between'));
        $messages += array("last_name.required"=>trans($msg_validation.'last_name.required'));
        $messages += array("last_name.between"=>trans($msg_validation.'last_name.between'));
        $messages += array("phone.nullable"=>trans($msg_validation.'phone.nullable'));
        $messages += array("phone.digits_between"=>trans($msg_validation.'phone.digits_between'));
        $messages += array("email.required"=>trans($msg_validation.'email.required'));
        $messages += array("email.unique"=>trans($msg_validation.'email.unique'));
        return Validator::make($inputs,$rules,$messages);
    }
}

<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationLdCategory{
    public static function LdCategoryRegister($request, & $msg_validation=null){
        $msg_validation=config('admin.lang.validation.ldCategory').'register.';
        $inputs = $request;
        $rules= array();
        /* $rules += array('code'=>['required','between:1,50',ValidationService::BuildUniqueField('categories','code',$request['code'])]); */
        $rules += array('name'=>['required','between:1,200',ValidationService::BuildUniqueField('categories','name',$request['name'])]);

        $messages=array();
        /* $messages+=array("code.required"=>trans($msg_validation."code.required")); */
        $messages += array("code.between"=>trans($msg_validation.'code.between'));
        $messages += array("code.unique"=>trans($msg_validation."code.unique"));
        $messages += array("name.required"=>trans($msg_validation."name.required"));
        $messages += array("name.between"=>trans($msg_validation.'name.between'));
        $messages += array("name.unique"=>trans($msg_validation."name.unique"));
        return Validator::make($inputs, $rules, $messages);
    }
}
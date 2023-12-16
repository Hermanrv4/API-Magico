<?php
namespace App\Http\Modules\Admin\Services;

use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationUbication{

    public static function UbicationRegister($request,&$msg_validation=null){
        $msg_validation= config('admin.lang.validation.ubication').'register.';
        $inputs = $request->all();
        $rules = array();
        if ($request['root_ubication_id']!=Parameter::GetByCode('default_id')) {
            $rules += array('root_ubication_id'=>['required','exists:ubications,id',Rule::notIn($request['id'])]);
        }
        $rules += array('code'=>['required','between:1,50',ValidationService::BuildUniqueField('ubications','code',$request['id'])]);
        $rules += array('name'=>['required','between:1,100']);        
        $messages=array();
        $messages += array("root_ubication_id.required"=>trans($msg_validation.'root_ubication_id.required'));
        $messages += array("root_ubication_id.exists"=>trans($msg_validation.'root_ubication_id.exists'));
        $messages += array("root_ubication_id.not_in"=>trans($msg_validation.'root_ubication_id.not_in'));
        $messages += array("code.required"=>trans($msg_validation.'code.required'));
        $messages += array("code.between"=>trans($msg_validation.'code.between'));
        $messages += array("code.unique"=>trans($msg_validation.'code.unique'));
        $messages += array("name.required"=>trans($msg_validation.'name.required'));
        $messages += array("name.between"=>trans($msg_validation.'name.between'));
        return Validator::make($inputs,$rules,$messages);
    }
    public static function UbicationDelete($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.ubication').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:ubications,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);
    }
}
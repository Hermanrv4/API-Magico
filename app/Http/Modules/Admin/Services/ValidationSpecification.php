<?php
namespace App\Http\Modules\Admin\Services;
use Illuminate\Support\Facades\Validator;
use App\Http\Modules\Admin\Services\ValidationService;

class ValidationSpecification{

    public static function SpecificationRegister($request,&$msg_validation=null){
        $msg_validation = config('admin.lang.validation.specification').'register.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('code'=>['required','between:1,50',ValidationService::BuildUniqueField('specifications','code',$request['id'])]);
        $rules += array('name'=>['required',ValidationService::BuildUniqueField('specifications','name',$request['id'])]);
        $rules += array('is_preview'=>['boolean']);
        $rules += array('is_color'=>['boolean']);
        $rules += array('is_html'=>['boolean']);
        $rules += array('is_image'=>['boolean']);
        $rules += array('is_globalfilter'=>['boolean']);
        $rules += array('needs_user_info'=>['boolean']);
        $messages = array();
        $messages += array('code.required'=>trans($msg_validation.'code.required'));
        $messages += array('code.between'=>trans($msg_validation.'code.between'));
        $messages += array('code.unique'=>trans($msg_validation.'code.unique'));
        $messages += array('name.required'=>trans($msg_validation.'name.required'));
        $messages += array('name.between'=>trans($msg_validation.'name.between'));
        $messages += array('name.unique'=>trans($msg_validation.'name.unique'));
        $messages += array('is_preview.boolean'=>trans($msg_validation.'is_preview.boolean'));
        $messages += array('is_color.boolean'=>trans($msg_validation.'is_color.boolean'));
        $messages += array('is_html.boolean'=>trans($msg_validation.'is_html.boolean'));
        $messages += array('is_image.boolean'=>trans($msg_validation.'is_image.boolean'));
        $messages += array('is_globalfilter.boolean'=>trans($msg_validation.'is_globalfilter.boolean'));
        $messages += array('needs_user_info.boolean'=>trans($msg_validation.'needs_user_info.boolean'));
        return Validator::make($inputs,$rules,$messages);
    }

    public static function SpecificationDelete($request,&$msg_validation=null)
    {
        $msg_validation = config('admin.lang.validation.specification').'delete.';
        $inputs = $request->all();
        $rules = array();
        $rules += array('id'=>['required','exists:specifications,id']);
        $messages = array();
        $messages += array('id.required'=>trans($msg_validation.'id.required'));
        $messages += array('id.exists'=>trans($msg_validation.'id.exists'));
        return Validator::make($inputs,$rules,$messages);

    }

}
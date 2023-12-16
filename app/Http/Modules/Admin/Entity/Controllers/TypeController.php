<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Type;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationType;

class TypeController extends ApiController{
    public function Get(Request $request){
        if (isset($request["type_id"])) {
            return $this->SendSuccessResponse(null,Type::GetById($request["type_id"]));
        }if (isset($request['type_group_id'])) {
            return $this->SendSuccessResponse(null,Type::GetByTypeGroupId($request['type_group_id']));
        }
        else{
            return $this->SendSuccessResponse(null,Type::all());
        }
    }

    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationType::TypeRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objType = new Type();
                //$is_update = $request["id"];
                $is_update = $request["id"]!=Parameter::GetByCode("default_id");
                if ($is_update) $objType = Type::GetById($request['id']);
                $objType->type_group_id = $request['type_group_id'];
                $objType->code = $request['code'];
                $objType->name = $this->LocalizationArray($request['name']);             
                $objType->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form_result.success'),array('result'=>$objType));
            }
        } catch (\Exception $ex) {
            if (config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function Delete(Request $request)
    {
        try{
            $msg_validation=null;
            $validator = ValidationType::TypeDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                //$objType = Type::GetById($request['id']);
                //$objType->delete();
                $objType = Type::DeleteById($request['id']);                
                return $this->SendSuccessResponse(null,array('result'=>$objType));
            }
            
        }catch(\Exception $ex){
            if (config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}

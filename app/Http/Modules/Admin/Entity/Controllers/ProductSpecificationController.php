<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\ProductSpecification;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationProductSpecification;

class ProductSpecificationController extends ApiController{

    public function Get(Request $request){
        if (isset($request["product_specification_id"])) {
            return $this->SendSuccessResponse(null,ProductSpecification::GetById($request["product_specification_id"]));
        }if (isset($request["specification_id"])) {
            return $this->SendSuccessResponse(null,ProductSpecification::GetBySpecificationId($request["specification_id"]));
        }
        if (isset($request['product_id'])) {
            return $this->SendSuccessResponse(null,ProductSpecification::GetByProductId($request['product_id']));
        }
        else{
            return $this->SendSuccessResponse(null,ProductSpecification::all());
        }
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationProductSpecification::ProductSpecificationRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objProductSpecification = new ProductSpecification();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objProductSpecification = ProductSpecification::GetById($request['id']);
                $objProductSpecification->product_id = $request['product_id'];
                $objProductSpecification->specification_id = $request['specification_id'];
                  $objProductSpecification->value = $this->LocalizationArray($request['value']);
                $objProductSpecification->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objProductSpecification));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function Delete(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationProductSpecification::ProductSpecificationDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                //$objProductSpecification = ProductSpecification::GetById($request['id']);
                //$objProductSpecification->delete();
                $objProductSpecification = ProductSpecification::DeleteById($request['id']);
                return $this->SendSuccessResponse(null,array('result'=>$objProductSpecification));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}

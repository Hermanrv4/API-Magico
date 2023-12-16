<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\CategorySpecification;
use App\Http\Modules\Admin\Services\ValidationCategorySpecification;

class CategorySpecificationController extends ApiController{
    public function Get(Request $request){
        if (isset($request["category_specification_id"])) {
            return $this->SendSuccessResponse(null,CategorySpecification::GetById($request["category_specification_id"]));
        }if (isset($request["category_id"])) {
            return $this->SendSuccessResponse(null,CategorySpecification::GetByCategoryId($request["category_id"]));
        }else{
            return $this->SendSuccessResponse(null,CategorySpecification::all());
        }
    }

    public function Register(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationCategorySpecification::CategorySpecificationRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objCategorySpecification = new CategorySpecification();
                $is_update = $request['id']!=Parameter::GetByCode('default_id');
                if ($is_update) $objCategorySpecification = CategorySpecification::GetById($request['id']);
                $objCategorySpecification->category_id = $request['category_id'];
                $objCategorySpecification->specification_id = $request['specification_id'];
                $objCategorySpecification->is_filter = $request['is_filter'];
                $objCategorySpecification->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objCategorySpecification));
            }
        } catch (\Exception $ex) {
            if (config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function Delete(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationCategorySpecification::CategorySpecificationDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objCategorySpecification = CategorySpecification::GetById($request['id']);
                $objCategorySpecification->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objCategorySpecification));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}
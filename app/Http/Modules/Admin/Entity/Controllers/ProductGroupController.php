<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\ProductGroup;
use App\Http\Models\Database\Product;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationProductGroup;

class ProductGroupController extends ApiController{

    public function Get(Request $request){
        if (isset($request["product_group_id"])) {
            return $this->SendSuccessResponse(null,ProductGroup::GetById($request["product_group_id"]));
        }if (isset($request["category_id"])) {
            return $this->SendSuccessResponse(null,ProductGroup::GetByCategoryId($request["category_id"]));
        }
        else{
            return $this->SendSuccessResponse(null,ProductGroup::all());
        }
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationProductGroup::ProductGroupRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objProductGroup = new ProductGroup();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objProductGroup = ProductGroup::GetById($request['id']);
                $objProductGroup->category_id = $request['category_id'];
                $objProductGroup->code = $request['code'];
                $objProductGroup->name = $this->LocalizationArray($request['name']);
                $objProductGroup->description = $request['description'];
                $objProductGroup->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objProductGroup));
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
            $validator = ValidationProductGroup::ProductGroupDelete($request,$msg_validation);
            $validator->after(function($validator) use ($request,$msg_validation){                
                if(count(Product::GetByTableId(ProductGroup::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_product'));
            });
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objProductGroup = ProductGroup::DeleteById($request['id']);
                return $this->SendSuccessResponse(null,array('result'=>$objProductGroup));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function ProductGroupGetOrderSale(Request $request){
        return $this->SendSuccessResponse(null, ProductGroup::withoutGlobalScopes()->GetGroupOrderBillingSale($request['date_start'],$request['date_end'])->get());
    }
    public function ProductGroupGetBillingSaleOfDate(Request $request){
        return $this->SendSuccessResponse(null, ProductGroup::withoutGlobalScopes()->GetGroupOrderBillingSaleOfDate($request['date_start'], $request["date_end"])->get());
    }
}

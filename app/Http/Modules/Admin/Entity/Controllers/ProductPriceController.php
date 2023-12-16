<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\Currency;
use App\Http\Models\Database\ProductPrice;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationProductPrice;

class ProductPriceController extends ApiController{

    public function Get(Request $request){
        if (isset($request["product_price_id"])) {
            return $this->SendSuccessResponse(null,ProductPrice::GetById($request["product_price_id"]));
        }
        if(isset($request['currency_id'])){
            return $this->SendSuccessResponse(null,ProductPrice::GetByCurrencyId($request['currency_id']));
        }if(isset($request['product_id'])){
            return $this->SendSuccessResponse(null,ProductPrice::GetByProductId($request['product_id']));
        }
        else{
            return $this->SendSuccessResponse(null,ProductPrice::all());
        }
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationProductPrice::ProductPriceRegister($request,$msg_validation);
 /*            $validator->after(function($validator) use ($request,$msg_validation){
                if(ProductPrice::GetByProductIdAndCurrencyId($request['product_id'],$request['currency_id'])!=null)$validator->errors()->add('form',trans($msg_validation.'form.exist_product_price'));
            });  */
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objProductPrice = new ProductPrice();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objProductPrice = ProductPrice::GetById($request['id']);
                $objProductPrice->product_id = $request['product_id'];
                $objProductPrice->currency_id = $request['currency_id'];
                $objProductPrice->regular_price = $request['regular_price'];
                $objProductPrice->online_price = $request['online_price'];
                $objProductPrice->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objProductPrice));
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
            $validator = ValidationProductPrice::ProductPriceDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objProductPrice = ProductPrice::DeleteById($request['id']);
                return $this->SendSuccessResponse(null,array('result'=>$objProductPrice));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}

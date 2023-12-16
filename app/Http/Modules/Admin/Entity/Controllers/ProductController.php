<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\ProductGroup;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\OrderDetail;
use App\Http\Models\Database\ProductPrice;
use App\Http\Models\Database\ProductSpecification;
use App\Http\Models\Database\WishListProduct;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationProduct;

class ProductController extends ApiController{

    public function Get(Request $request){
        if (isset($request["product_id"])) {
            return $this->SendSuccessResponse(null,Product::GetById($request["product_id"]));
        }if(isset($request['product_group_id'])){
            return $this->SendSuccessResponse(null,Product::GetByProductGroup($request['product_group_id']));
        }
        else{
            return $this->SendSuccessResponse(null,Product::all());
        }
    }

    public function ChangeStatus(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationProduct::ChangeStatus($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {              
                /* $objProduct = Product::Desactivate($request['id']); */
                $objProduct=Product::ChangeActive($request["id"], $request["value"]);
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objProduct));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse($ex->getMessage());
        }
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationProduct::ProductRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objProduct = new Product();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objProduct = Product::GetById($request['id']);
                $objProduct->product_group_id = $request['product_group_id'];
                $objProduct->sku = $request['sku'];
                $objProduct->url_code = $this->LocalizationArray($request['url_code']);
                $objProduct->name = $this->LocalizationArray($request['name']);
                $objProduct->description = $this->LocalizationArray($request['description']);
                $objProduct->is_for_catalogue = $request['is_for_catalogue'];
                $objProduct->is_active = $request['is_active'];
                $objProduct->stock = $request['stock'];
                $objProduct->shipping_size = $request['shipping_size'];
                $objProduct->photos = $request['photos'];
                $objProduct->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objProduct));
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
            $validator = ValidationProduct::ProductDelete($request,$msg_validation);
            $validator->after(function($validator) use ($request,$msg_validation){                
                if(count(Cart::GetByTableId(Product::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_cart'));
                if(count(OrderDetail::GetByTableId(Product::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_order_detail'));
                if(count(ProductPrice::GetByTableId(Product::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_product_price'));
                if(count(ProductSpecification::GetByTableId(Product::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_product_specification'));
                if(count(WishListProduct::GetByTableId(Product::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_wish_list_product'));
            });
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objProduct = Product::DeleteById($request['id']);
                return $this->SendSuccessResponse(null,array('result'=>$objProduct));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
    public function GetProductOrderSaleDate(Request $request){
        return $this->SendSuccessResponse(null, Product::withoutGlobalScopes()->GetByProductAndDate($request['date_start'], $request['date_end'])->get());
    }
    public function GetProductBillingSaleOfDate(Request $request){
        return $this->SendSuccessResponse(null, Product::withoutGlobalScopes()->GetProductBillingSalesOfDate($request['date_start'], $request['date_end'])->get());
    }
    public function ChangeName(Request $request){
        /* $listProd=Product::all(); *//* 
        $listProd=ProductSpecification::all(); */
        /* $list=array();
        for($item=0; $item<count($listProd); $item++){
            $nametest=$listProd[$item]['name_localized'];
            $array=json_decode($listProd[$item]['name'],true);
            $array_code=json_decode($listProd[$item]['url_code'],true);
            $array_descripcion=json_decode($listProd[$item]['description'], true);
            $url_codetest=$listProd[$item]['url_code_localized'];
            $url_description=$listProd[$item]['description_localized'];
            $array[0]['pt']=$nametest;
            $array_code[0]['pt']=$url_codetest;
            $array_descripcion[0]['pt']=$url_description;
            $objProduct=Product::GetById($listProd[$item]['id']);
            $objProduct->name=json_encode($array);
            $objProduct->url_code=json_encode($array_code);
            $objProduct->description=json_encode($array_descripcion);
            $objProduct->save();
            $list[]=$objProduct;
        } */
        /* for($item=0;$item<count($listProd); $item++){
            $array_value=json_decode($listProd[$item]['value'],true);
            $value_localized=$listProd[$item]['value_localized'];
            $array_value[0]['pt']=$value_localized;
            $objSpecificationProd=ProductSpecification::GetById($listProd[$item]['id']);
            $objSpecificationProd->value=json_encode($array_value);
            $objSpecificationProd->save();
            $list[]=$array_value;
        } */
        /* return $this->SendSuccessResponse(null, $list); */
    }
}

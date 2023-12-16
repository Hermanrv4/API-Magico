<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\ShippingPrice;
use App\Http\Models\Database\User;
use Exception;
use Illuminate\Http\Request;

class CartController extends ApiController{
    public function Get(Request $request){
        return $this->SendSuccessResponse(null,Cart::GetByUserId($request["user_id"]));
    }
    public function Add(Request $request){
        $objProduct = Product::GetById($request["product_id"]);
        $objCart = Cart::GetByUserIdAndProductId($request["user_id"],$objProduct->id);
        Cart::DeleteByUserIdAndProductId($request["user_id"],$objProduct->id);

        $objNewCart = new Cart();
        $objNewCart->user_id = $request["user_id"];
        $objNewCart->product_id = $request["product_id"];
        if($request["replace"]){
            $objNewCart->quantity = $request["qty"];
        }else{
            $objNewCart->quantity = ($objCart!=null?$objCart->quantity:0) + $request["qty"];
        }
        $objNewCart->observations = $request["observations"];
        if($request["replace"]==null){
            $objNewCart->observations = $objCart->observations;
        }

        if($objNewCart->quantity>0){
            $objNewCart->save();
        }
        return $this->SendSuccessResponse(null,Cart::GetByUserId($request["user_id"]));
    }
    public function ClearForUser(Request $request){
            $cartProd=Cart::DeleteByTableId(User::class,$request["user_id"]);
            return $this->SendSuccessResponse(null, $cartProd);
    }
    public function ClearForOrder(Request $request){
        try{
            if(isset($request['token']) && $request['token']!=null){
                $cartProd=Cart::DeleteProductForUserOrder($request['user_id'], $request['token']);
                return $this->SendSuccessResponse(null,$cartProd);
            }
        }catch(Exception $e){
            return $this->SendErrorResponse(null, $e);
        }
    }
}

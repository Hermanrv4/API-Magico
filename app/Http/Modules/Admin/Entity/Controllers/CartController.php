<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use Carbon\Carbon;
use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\User;
use Exception;
use Illuminate\Http\Request;

class CartController extends ApiController{

    public function GetCartsByLastUpdate(Request $request){

        $daysCartReminder = Parameter::GetByCodeValue("daysCartReminder");
        $today = Carbon::parse(date("Ymd"));

        $usersWithCart = Cart::GetCartsByLastUpdate();
        $currentId = 1;
        $currentCarts = array();
        
        foreach($usersWithCart as $rowCart) {
            
            $lastUpdate = Carbon::parse($rowCart->last_cart_date);
            $lastUpdateFormated = Carbon::parse($lastUpdate->format('d-m-Y'));
            $diffDays = $lastUpdateFormated->diffInDays($today);
            
            if($currentId !== $rowCart->id){
                $currentId = $rowCart->id;
                
                $productsByUserId = Cart::GetProductsByUserId($rowCart->id);
                $currentProducts = array();

                foreach($productsByUserId as $rowProduct ){
                    $rowProduct->url_code = json_decode($rowProduct->url_code);
                    $rowProduct->name = json_decode($rowProduct->name);
                    $rowProduct->description = json_decode($rowProduct->description);
                    $rowProduct->gen_keys = json_decode($rowProduct->gen_keys);
                    array_push($currentProducts, $rowProduct);
                }

                if(strval($diffDays) === $daysCartReminder->value){
                    $rowCart->products = $currentProducts;
                    array_push($currentCarts, $rowCart);
                }
            }
        }

        return $this->SendSuccessResponse(null, $currentCarts);
    }
}

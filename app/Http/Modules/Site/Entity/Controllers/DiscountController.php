<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Discount;
use App\Http\Models\Database\ShippingPrice;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Type;
use App\Http\Models\Database\Ubication;
use Illuminate\Http\Request;
 
class DiscountController extends ApiController{
    public function GetAllAllowed(Request $request){
        $allowed_cards = Parameter::GetByCode('allowed_cards');
        $cupon_param = Parameter::GetByCode('cupon_param');
        $card_param = Parameter::GetByCode('card_param');
        $discounts = Discount::GetAllDiscounts($cupon_param,$card_param,json_decode($allowed_cards));
        return $this->SendSuccessResponse(null,$discounts);
    }
    public function GetByReference(Request $request){
        $discount = null;
        $allowed_cards = Parameter::GetByCode('allowed_cards');
        $DiscountType = Type::GetByCode(Parameter::GetByCode('card_param'));
        if(isset($request["code"])){
            $discount = Discount::ValidatCoupon($request["code"],json_decode($allowed_cards),$DiscountType->id);
        }else{
            $discount = Discount::ValidatDiscount($request["id"]);
        }
        return $this->SendSuccessResponse(null,$discount);
    }
    public function GetByCodeCard(Request $request){
        $discount = null;
        $allowed_cards = json_decode(Parameter::GetByCode('allowed_cards'));
        $DiscountType = Type::GetByCode(Parameter::GetByCode('card_param'));
        $existCard = false;
        $coincidences = array();
        $cardsCoincidences = array();
        $existCard = false;
        if(strlen($request["digits"])>5){
            for($j=0;$j<count($allowed_cards);$j++){
                $bins = explode(',',$allowed_cards[$j]->bin);
                //Si tiene varios patrones
                if(count($bins)>1){
                    for($i=0;$i<count($bins);$i++){
                        $bins2 = explode('-',$bins[$i]);
                        //Si tiene rangos
                        if(count($bins2)>1){
                            $binVal = $bins2[0];
                            $BinVerify = substr($request["digits"],0,strlen($binVal));
                            if($BinVerify>=$bins2[0] && $BinVerify<=$bins2[1]){
                                if(in_array($allowed_cards[$j]->code,$cardsCoincidences)==false){
                                    $existCard = true;
                                    $coincidences[] = $allowed_cards[$j];
                                    $cardsCoincidences[] = $allowed_cards[$j]->code;
                                }
                            }
                        }
                        //Si solo tiene valores fijos
                        else{
                            $binVal = $bins2[0];
                            $BinVerify = substr($request["digits"],0,strlen($binVal));
                            if($binVal==$BinVerify){
                                if(in_array($allowed_cards[$j]->code,$cardsCoincidences)==false){
                                    $existCard = true;
                                    $coincidences[] = $allowed_cards[$j];
                                    $cardsCoincidences[] = $allowed_cards[$j]->code;
                                }
                            }
                        }
                    }
                }
                //Si solo tiene un patron unico
                else{
                    $binVal = $bins[0];
                    $BinVerify = substr($request["digits"],0,strlen($binVal));
                    if($binVal==$BinVerify){
                        if(in_array($allowed_cards[$j]->code,$cardsCoincidences)==false){
                            $existCard = true;
                            $coincidences[] = $allowed_cards[$j];
                            $cardsCoincidences[] = $allowed_cards[$j]->code;
                        }
                    }
                }
            }
            if($existCard == true){
                $discount = Discount::GetByIdCardType($coincidences[0]->code,$DiscountType->id);
            }
        }
        return $this->SendSuccessResponse(null,$discount);
    }
    public static function GetById($id){
        return Discount::whereRaw('id = ?',[$id])->get();
    }
    public function GetByCodeReference(Request $request){

    }
}

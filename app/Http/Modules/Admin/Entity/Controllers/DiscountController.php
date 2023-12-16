<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Discount;
use App\Http\Models\Database\Parameter;
use App\Http\Modules\Admin\Services\ValidationDiscount;
use Exception;
use Illuminate\Http\Request;
//use App\Http\Modules\Admin\Services\ValidationElectronicBillingSale;

class DiscountController extends ApiController{

    public function GetDiscount(Request $request){
        if(isset($request['id_type_discount']) && $request['id_type_discount']!=null || $request['id_type_discount']!=''){
            return $this->SendSuccessResponse(null, Discount::where((new Discount)->getTable().'.id_type_discounts', "=", $request['id_type_discount'])->get());
        }else if(isset($request['currency_id']) && $request["currency_id"]!=null || $request['currency_id']!=''){
            return $this->SendSuccessResponse(null, Discount::where((new Discount)->getTable().'.currency_id', "=", $request['currency_id'])->get());
        }else if(isset($request['discount_id']) && $request['discount_id']!=null || $request['discount_id']!=''){
            return $this->SendSuccessResponse(null, Discount::find($request['discount_id']));
        }else{
            return $this->SendSuccessResponse(null, Discount::all());
        }
    }
    public function RegisterDiscount(Request $request){
        try{
            $msg_validation=null;
            $validator=ValidationDiscount::DiscountRegister($request, $msg_validation);
            if($validator->fails()){
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'), $validator->errors());
            }else{
                $objDiscount=new Discount();
                $is_update= $request['coupon_id']!=Parameter::GetByCode('default_id');
                if($is_update) $objDiscount = Discount::GetById($request['coupon_id']);
                $objDiscount->name=$this->LocalizationArray($request['name']);
                $objDiscount->code=$request["code"];
                $objDiscount->description=$this->LocalizationArray($request["description"]);
                $objDiscount->currency_id=$request["currency_id"];
                $objDiscount->value=$request["value"];
                $objDiscount->affectation=$request["affectation"];
                $objDiscount->free_shipping = $request["free_shipping"];
                $objDiscount->is_acumulate = $request["is_acumulate"];
                $objDiscount->max_uses = $request["max_uses"];
                $objDiscount->acumulate_uses = $request['acumulate_uses'];
                $objDiscount->id_type_discounts = $request["id_type_discounts"];
                $objDiscount->id_cards = $request["id_cards"];
                $objDiscount->date_start = $request["date_start"];
                $objDiscount->date_end = $request["date_end"];
                $objDiscount->save();
                return $this->SendSuccessResponse(null, $objDiscount);
            }
        }catch(Exception $e){
            return dd($e);
        }
    }
    public function GetAffectation(Request $request){
        if(isset($request['discount_id']) && $request['discount_id'] != '' || $request['discount_id'] != null){
            if(Discount::where( (new Discount)->getTable().'.id', "=", $request['discount_id'])->exists()){
                $objDiscount=Discount::find($request['discount_id']);
                /* $objDiscount->affectation; */
                return $this->SendSuccessResponse(null, array( ($objDiscount->affectation == null ? array() : json_decode($objDiscount->affectation,true)) ));
            }else{
                return $this->SendErrorResponse(null, ['El id ingresado no es valido']);
            }
        }else{
            return $this->SendErrorResponse(null, ['']);
        }
    }
    public function saveAffectation(Request $request){
        if(Discount::where((new Discount)->getTable().'.id', "=", $request['code_discount'])->exists()){
            $objDiscount=Discount::find($request['code_discount']);
            $objDiscount->id=$request['code_discount'];
            $objDiscount->affectation=$request['affectation'];
            $objDiscount->save();
            return $this->SendSuccessResponse(null, $objDiscount);
        }else{
            return $this->SendErrorResponse(null, ['El id ingresado no existe']);
        }
    }
}
<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Common\Helpers\StringHelper;
use App\Http\Models\Database\Address;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Currency;
use App\Http\Models\Database\Order;
use App\Http\Models\Database\OrderDetail;
use App\Http\Models\Database\ListProductsEvent;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Event;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\ProductGroup;
use App\Http\Models\Database\ProductPrice;
use App\Http\Models\Database\Discount;
use App\Http\Models\Database\Provider;
use App\Http\Models\Database\ShippingPrice;
use App\Http\Models\Database\Type;
use App\Http\Models\Database\Ubication;
use App\Http\Models\Database\User;
use App\Http\Modules\Site\Services\GenService;
use App\Http\Modules\Site\Services\ValidationService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends ApiController{
    public function Get(Request $request){
        return $this->SendSuccessResponse(null,Order::GetById($request["order_id"]));
    }
    public function GetByToken(Request $request){
		
		$order = Order::GetByToken($request["token"]);
        if($order!=null){
            if($order->is_for_event!=0){
                $event = Event::GetById($order->event_id);
                $user_event = User::GetById($order->user_id);
                $data_info = (array)json_decode($order->receiver_info);
                
                $order->email_from = $user_event->email;
                $order->name_from = $user_event->first_name;
                $order->last_name_from = $user_event->last_name;
                $order->email_to =  $data_info["receiver_email"];
                $order->name_to =  $data_info["receiver_first_name"];
            }
            return $this->SendSuccessResponse(null,$order);
        }else{
            return $this->SendErrorResponse(null, ['']);
        }
    }
	public function GetById(Request $request){
        return $this->SendSuccessResponse(null,Order::GetById($request["id"]));
    }
    public function UpdatePaymentStatus(Request $request){
        $objOrder = Order::GetById($request["order_id"]);
        $objOrder->payment_response = $request["response"];

        if($request["pay_code"]!=null){
            $objOrder->transaction_pay_code = $request["pay_code"];
        }else{
            $trx = StringHelper::GetTrxPayCode();
            $objOrder->transaction_pay_code = $trx;
        }
        $objOrder->save();
        $objOrder = Order::GetById($request["order_id"]);
        $voucher = null; 
        if(GenService::GenIntegrated()==1){ 
            if(isset($request["voucher"]) && $request["voucher"]!=null){
                $voucher = $request["voucher"];
            } 
            if($request["status"]==Parameter::GetByCode('approved_status') && $objOrder["payment_status"]!=Parameter::GetByCode('approved_status') && ($objOrder->gen_response==0 || $objOrder->gen_response!=null)){
                $gen_response=GenService::GenRegisterOrder($request["order_id"],$voucher);
                $objOrder->gen_response = $gen_response; 
            }
            $objOrder->payment_status = $request["status"];
        }else{
            $objOrder->payment_status = $request["status"];
        }
        // validamos que el estado de pago sea aprobado
        if($request["status"]=='approved'){
            $objOrder->status_type_id = 1;
        }
        // fin
        $objOrder->save();
        return $this->SendSuccessResponse();
    }
    public static function UpdateStatusOrder($token,$status){
        $operation=false;
        $objOrder = Order::GetByToken($token);
        switch($status){
            case 'approved': $objOrder->status_type_id = 1;$operation=true;$objOrder->payment_status =$status;
            /*---  VALIDAR SI HAY GEN ---*/
            if(GenService::GenIntegrated()==1){
                $gen_response=GenService::GenRegisterOrder($objOrder->id,null);
                $objOrder->gen_response = $gen_response; 
            }
            /*---  SE TERMINA ---*/ 
            ;break;
            case 'pending':  $objOrder->status_type_id = 2;$operation=true;$objOrder->payment_status =$status;break;
            case 'canceled':  $objOrder->status_type_id = 3;$operation=true;$objOrder->payment_status =$status;break;
            default: $operation=false;
        }

        $objOrder->save();
        return $operation;
    }
    public function Register(Request $request){
        try {
            if(isset($request["address_option"])==false){
                $request["address_option"] = 1;
            }
            if(isset($request["shop_id"])==false){
                $request["shop_id"] = null;
            }
            if(isset($request["type_store"])==false){
                $request["type_store"] = null;
            }


            if($request["shop_id"]!=null){
                $request["address"] = 'NNNNNNNNNNNNNN';
                $request["ubication_id"] = 1;
            }
            $request["address_option"] = intval($request["address_option"]);
            $paymCode = Type::GetByCode('PAYMENTMET');
            $eternCode = Type::GetByCode('ETERNAL');
    
            $payment=Discount::where((new Discount)->getTable().'.id_type_discounts', "=", $paymCode->id)->orWhere((new Discount)->getTable().'.id_type_discounts', "=", $eternCode->id)->get();
  
            $list=array();
            for($item=0; $item<count($payment); $item++){
                $list[$item]['id_discount']=$payment[$item]->id;
                $list[$item]['id_cards']=$payment[$item]->id_cards;
                $list[$item]['value_discount']=$payment[$item]->value;
                $list[$item]['currency_id']=$payment[$item]->currency_id;
                $list[$item]['free_shipping']=$payment[$item]->free_shipping;
            }
            $listCards=json_decode(Parameter::GetByCode('allowed_cards'));

            $token = null;
            $msg_validation = null;
            $totalShippingPrice = 0;
            $lstCart = array();
            $objCurrency = null;
            $validator = ValidationService::OrderRegister($request,$msg_validation);
            $validator->after(function($validator) use ($request,$msg_validation,&$totalShippingPrice,&$lstCart,&$objCurrency){

                if(!isset($request["currency_id"])){
                    $objCurrency = Currency::GetByCode($request["currency_code"]);
                }else{
                    $objCurrency = Currency::GetById($request["currency_id"]);
                }

                /*--- SE VALIDA QUE EL CARRITO SE HAYA ENVIADO DE MANERA CORRECTA ---*/
                try{
                    $lstCart = json_decode($request["cart"],true);
                }catch (\Exception $ex){
                    $validator->errors()->add('forms', trans($msg_validation.'form.error.invalid_cart'));
                }

                /*--- SE VALIDA EL COSTO DE ENVÍO SI NO TIENE TIENDA---*/
                if($request["shop_id"]==null){
                   try {
                    $objSP = ShippingPrice::GetByUbicationIdAndCurencyId($request["ubication_id"], $objCurrency->id);
                    if($objSP == null || $objSP->price < 0) {
                        $validator->errors()->add('forms', trans($msg_validation.'form.error.invalid_shipping_price'));
                    }
                    $totalShippingPrice = $totalShippingPrice + $objSP->price;
                    }catch(\Exception $ex){
                        $validator->errors()->add('forms', trans($msg_validation.'form.error.invalid_shipping_price'));
                    } 
                }
                
                $responseLockCart=array();
                /*--- SE VALIDA EL CARRITO, CANTIDADES Y PRODUCTOS VÁLIDOS ---*/
     
                for($i=0;$i<count($lstCart);$i++){
					$qyt=0;
					
					if(isset($lstCart[$i]["qty"])){
						$qyt = $lstCart[$i]["qty"];
					}else{
						$qyt = $lstCart[$i]["quantity"];
					}
					
                    $stock = 0;
                    $producto = Product::GetById($lstCart[$i]["product_id"]);
                    
                    $disp = 0;
                    if(GenService::GenIntegrated()==1 || GenService::GenIntegrated()=='1'){
                        $gen_keys = json_decode($producto["gen_keys"]);
                        $stock = GenService::GetStockForItem($gen_keys->item_no,$gen_keys->mfg_ser_lot_no);
                        if($stock!=0){
                           $LokItm = GenService::LockItemStockForUser($gen_keys,$qyt,$request["token"]);
                            if($LokItm!="0"){
                                $responseLockCart[] = $LokItm;
                            } 
                        }
                    }else{
                        $stock = $producto["stock"];
                    }
       
                    $disp = $stock - $qyt;

                    if((Product::GetById($lstCart[$i]["product_id"] == null)) || ($qyt<=0)){
                        $validator->errors()->add('forms', trans($msg_validation.'form.error.invalid_cart')); 
                        break;
                    }
                    if($disp<0){
                        $validator->errors()->add('forms', str_replace(":qty",$stock,trans($msg_validation.'form.error.product_min_stock'))); 
                    }
                    if(count($responseLockCart)>0){
                        $validator->errors()->add('forms', str_replace(":qty",$stock,trans($msg_validation.'form.error.product_min_stock'))); 
                    }
                }
                
            });
                if($validator->fails()){
                    return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
                }
                if(!isset($request["currency_id"])){
                    $objCurrency = Currency::GetByCode($request["currency_code"]);
                }else{
                    $objCurrency = Currency::GetById($request["currency_id"]);
                }
               
                /*--- REGISTRAMOS LA DIRECCIÓN DEL USUARIO PARA ESTA COMPRA ---*/
				$user_find = User::GetByEmail($request["receiver_email"]);
				$objAddress = null;

                if($request["shop_id"]==null){
                    if($user_find!=null){//SI ENCUENTRA

                        if($request["address_option"]==0 || $request["address_option"]=="0"){
                            $objAddress = Address::GetByAllData($user_find->id,$request["ubication_id"],$request["address"]);
                        }else{
                        //if($objAddress==null){//SI NO ENCUENTRA REGISTRADA
                                $objAddress = new Address();
                                $objAddress->user_id = $user_find->id;
                                $objAddress->wish_list_id = null;
                                $objAddress->ubication_id = $request["ubication_id"];
                                $objAddress->address = $request["address"];
                                $objAddress->phone = $request["receiver_phone"];
                                $objAddress->save();				
                            //}  
                        }
                    }else{
                        $user = new User();
                        $user->first_name = $request["receiver_first_name"];
                        $user->last_name = $request["receiver_last_name"];
                        $user->dni = $request["receiver_dni"];
                        $user->email = $request["receiver_email"];
                        $user->save();

                        $user_find = User::GetByEmail($request["receiver_email"]);
                        //$objAddress = Address::GetByAllData($user_find->id,$request["ubication_id"],$request["address"]);
                        //if($objAddress==null){//SI NO ENCUENTRA REGISTRA
                            $objAddress = new Address();
                            $objAddress->user_id = $user_find->id;
                            $objAddress->wish_list_id = null;
                            $objAddress->ubication_id = $request["ubication_id"];
                            $objAddress->address = $request["address"];
                            $objAddress->phone = $request["receiver_phone"];
                            $objAddress->save();	
                        //}	
                        
                    }                    
                }
 
				$user_find = User::GetByEmail($request["receiver_email"]);
				$rec_first_name = "";
				$rec_last_name = "";
				$rec_email = "";
				$rec_phone = "";
				$rec_dni = "";
				if(isset($request["event"])){
					
					$event = Event::GetById($lstCart[0]["event_id"]);
					
					$user_event = User::GetById($event->user_id);
					
					$rec_first_name = $user_event->first_name;
					$rec_last_name = $user_event->last_name;
					$rec_email = $user_event->email;
					$rec_phone = "";
					$rec_dni = "";
				}else{
					$rec_first_name = $request["receiver_first_name"];
					$rec_last_name = $request["receiver_last_name"];
					$rec_email = $request["receiver_email"];
					$rec_phone =  $request["receiver_phone"];
					$rec_dni = $request["receiver_dni"];
				}
                /*--- REGISTRAMOS LA ORDEN ---*/
                $objOrder = new Order();
                $objOrder->user_id = $user_find->id;
                $objOrder->wish_list_id = null;
                $objOrder->billing_address_id = null;
                $objOrder->shipping_address_id = null;
                if($request["shop_id"]==null){
				    $objAddress = Address::GetByAllData($user_find->id,$request["ubication_id"],$request["address"]);
                    $objOrder->billing_address_id = $objAddress->id;
                    $objOrder->shipping_address_id = $objAddress->id;
                }else{
                    $objOrder->billing_address_id = $request["shop_id"];
                    $objOrder->shipping_address_id = $request["shop_id"];
                }
                $objOrder->currency_id = $objCurrency->id;
                $objOrder->tax_percentaje = 0;
                $objOrder->tax_amount = 0;
                $shipping_cost = $totalShippingPrice;
                if(isset($request["shipp_c"])){
                    $shipping_cost = $request["shipp_c"];
                }

                $aditional_info = array(
                    "receiver_first_name" => $rec_first_name,
                    "receiver_last_name" => $rec_last_name,
                    "receiver_email" => $rec_email,
                    "receiver_phone" => $rec_phone,
                    "receiver_dni" => $rec_dni,
                    "is_gift"=>$request['is_gift'] ?? 0,
                );
                if(isset($request["city"])){
                    if($request["city"]!=null && $request["city"]!=''){
                        $aditional_info["city_id"] =$request["city"];
                    }
                }
				$aditional_info["bs_name"]="";
				$aditional_info["bs_ruc"]="";
				if(isset($request["facture"])){
					if($request["facture"]==true){
						$aditional_info["bs_name"]=$request["bs_name"];
						$aditional_info["bs_ruc"] =$request["bs_ruc"];
					}
				}
          
                $objOrder->type_store = $request["type_store"];
                $objOrder->id_shop = $request["shop_id"]; 
                $objOrder->sub_total = 0;
                $objOrder->total = 0;
                $objOrder->transaction_pay_code = null;
                $objOrder->payment_response = null;
                $objOrder->payment_status = null;
                $objOrder->aditional_info = json_encode($aditional_info);
                $objOrder->receiver_info = json_encode(array(
                    "receiver_first_name" => $rec_first_name,
                    "receiver_last_name" => $rec_last_name,
                    "receiver_email" => $rec_email,
                    "receiver_phone" => $rec_phone,
                    "receiver_dni" => $rec_dni,
                ));
                if(isset($request["for_my"])){
                    if($request["for_my"]==true){
                        $tmpUser = User::GetByEmail($rec_email);
                        $tmpUser->first_name = $rec_first_name;
                        $tmpUser->dni = $rec_dni;
                        $tmpUser->last_name = $rec_last_name;
                        $tmpUser->phone = $request["receiver_phone"];
                        $tmpUser->email = $request["receiver_email"];
                        $tmpUser->save();
                    }
                }
                
                if(isset($request["observations"])){
                   $objOrder->observations = $request["observations"]; 
                }
				if(isset($request["payment_type"])){
                   $objOrder->payment_type = $request["payment_type"]; 
                }
                $mont_discount = 0;
                $objOrder->token = $request["token"];
                $objOrder->status_type_id = Type::GetByCode(config('app.value.db.type.states.pending'))->id;
                $objOrder->ordered_at = DateHelper::GetNow();
				if(isset($request["event"])){
					$objOrder->is_for_event = 1;
					$objOrder->event_id = $lstCart[0]["event_id"];
				}
               
                $objOrder->shipping_cost = 0;
                $objOrder->save();

                /*--- REGISTRAMOS EL DETALLE DE LA ORDEN ---*/
                $subtotal = 0;
                for ($i = 0; $i < count($lstCart); $i++) {
                    $objPP = ProductController::GetProductData($lstCart[$i]["product_id"], $objCurrency->id);
                    $objOD = new OrderDetail();
                    $objOD->order_id = $objOrder->id;
                    $objOD->product_id = $lstCart[$i]["product_id"];
					$quantity = 0;
					if(isset($lstCart[$i]["qty"])){
						$quantity = $lstCart[$i]["qty"];
					}else{
						$quantity = $lstCart[$i]["quantity"];
					}
					
                    $objOD->price = $objPP->online_price;
                    $objOD->quantity = $quantity;
					if(isset($lstCart[$i]["observations"])){
						$objOD->observations = $lstCart[$i]["observations"];
					}else{
						$objOD->observations = "";
					}
                    
					if(isset($request["event"])){
						ListProductsEvent::UpdateByUserProduct($lstCart[$i]["event_id"],$lstCart[$i]["product_id"],$lstCart[$i]["qty"]);
					}
					
                    $objOD->save();
                    $subtotal = $subtotal + ($objOD->price * $objOD->quantity);
                }
                /*--- ACTUALIZAMOS LA ORDEN CON LA INFORMACIÓN DE PRECIOS ---*/
                $objOrder = Order::GetById($objOrder->id);
                $objOrder->tax_percentaje = floatval(Parameter::GetByCode('tax'));
                $objOrder->sub_total = $subtotal;
                /*--- VARIABLES A TRABAJAR PARA EL CÁLCULO FINAL ---*/
                $newSubTotal = $objOrder->sub_total;
                $newDiscount = 0;
                /*--- VALIDACION DE BIN NUMERO DE TARJETA ---*/
                $status_pay=false;
 
                if(isset($request['card_number']) && $request['card_number']!=null && $request['card_number']!=""){
                    $card_number=$request['card_number'];
                    // traer un payment
                    $code=null;

                    for($item=0; $item<count($listCards); $item++){
                        for($a=0; $a<count($list); $a++){
                            if($listCards[$item]->code == $list[$a]['id_cards']){
                                $code=$listCards[$item];
                            }
                        }
                    }

                    $bin=$code->bin;
                    // validar el bin
                    if(strpos($bin, ',')){
                        $bin=explode(',', $code->bin);
                        $len=strlen($bin[0]);
                        $value=substr($card_number, 0,$len);
                        $status_pay=in_array($value, $bin);
                    }else{
                        $value=substr($card_number, 0, strlen($bin));
                        if($value==$bin){
                            $status_pay=true;
                        }
                    }
                }
    
                if(isset($request['card_number']) == false){
                    $status_pay=true;
                }

                /*-- APLICACION DE DESCUENTO --*/
                $DiscObj = null;
                try{
                    /*-- VALIDAMOS SI TIENE ALGÚN TIPO DE DESCUENTO --*/
                    if(isset($request["id_discount"]) || $status_pay==true){
                        
                        $Type_Coupon = Type::GetByCode('CUPON');
                        $Type_Card = Type::GetByCode('PAYMENTMET');
                        /*-- SI SALE TRUE POR EL METODO DE VALIDACION DE CARD NUMBER --*/
                        if(isset($request['card_number']) && $request['card_number']!=null && $request['card_number']!=""){
                            if($status_pay == true){
                                $request["id_discount"] = $list[0]['id_discount'];
                            }else{
                                $request["id_discount"] = null;
                            }
                        }

                        /*-- VALIDAMOS SI EL ID DE DESCUENTO ES CORRECTO O EXISTE --*/
                        if($request["id_discount"]!=null){
                            $DiscObj = Discount::GetById($request["id_discount"]);
                            switch($DiscObj->id_type_discounts){
                            case $Type_Coupon->id: 
                                $allowed_cards = Parameter::GetByCode('allowed_cards');
                                $DiscObj = Discount::ValidatCoupon($DiscObj->code,json_decode($allowed_cards),$Type_Coupon); 
                                ;break;

                            case $Type_Card->id: 
                                $DiscObj = Discount::ValidatDiscount($request["id_discount"]);
                                ;break;

                            default: ;break;
                            }
                        }
                        /*-- EXTRAEMOS LOS VALORES EN CASO DE QUE EL DESCUENTO SEA VALIDO--*/
                        if($DiscObj!=null){
                            /*-- VERIFICAMOS SI ES UN CUPON --*/
                            if($DiscObj->id_type_discounts == $Type_Coupon->id){
                                /*-- VERIFICAMOS SI ES UN MONTO FIJO --*/
                                if($DiscObj->currency_id!=null){
                                    $newDiscount = $DiscObj->value;
                                }
                                /*-- ES UN MONTO POR PORCENTAJE --*/
                                else{
                                    $newDiscount = ($newSubTotal*$DiscObj->value)/100.0;
                                }
                            }
                            /*-- VERIFICAMOS SI ES UN DESCUENTO POR TARJETA O ETERNO --*/
                            else{
                                if($DiscObj->id_type_discounts == $Type_Card->id){
                                    /*-- VERIFICAMOS SI ES UN MONTO FIJO --*/
                                    if($DiscObj->currency_id!=null){
                                        $newDiscount = $DiscObj->value;
                                    }
                                    /*-- ES UN MONTO POR PORCENTAJE --*/
                                    else{
                                        $newDiscount = ($newSubTotal*$DiscObj->value)/100.0;
                                    }
                                }
                                /*-- VERIFICAMOS SI ES UN DESCUENTO ETERNO --*/
                                else{
                                    
                                }
                            }
                            $is_acumulated_disc = $DiscObj->is_acumulate;

                            if($is_acumulated_disc == 1){
                                Discount::PlusDiscount($request["id_discount"]);
                            }
                            $objOrder->id_discount = $request["id_discount"]; 
                            $objOrder->value_discount = $request["text_discount"] ?? $DiscObj->value;
                            /*-- VALIDAMOS SI TIENE COSTO DE ENVIO GRATIS --*/
                            if($DiscObj->free_shipping == 1){
                                $shipping_cost = 0; 
                            }
                        }
                    }
                    /*--- OBTENEMOS (AUMENTO O DESCUENTO POR MONTO DEFINIDO) ---*/
                    $Parameter_recharge=Parameter::GetByCode('recharge');
                    $Parameter_recharge= ($Parameter_recharge==null ? array() : json_decode($Parameter_recharge, true));

                    $objOrder->shipping_cost = $shipping_cost;

                    $objOrder->tax_amount = number_format(floatval(($newSubTotal) * ($objOrder->tax_percentaje / 100.0)),2, '.', ''); 
                    $subTotalWithOutIGV = number_format($newSubTotal - $objOrder->tax_amount,2, '.', '');

                    $tot_amount_ord = 0;
                    $tot_amount_ord = $subTotalWithOutIGV + $objOrder->tax_amount + $shipping_cost;

                    if(count($Parameter_recharge)>0){
                        if(number_format(floatval($objOrder->sub_total + $objOrder->shipping_cost),2, '.', '')< number_format($Parameter_recharge["reference"],2)){
                            $tot_amount_ord = $tot_amount_ord +  $Parameter_recharge["value"];
                        }
                    }
                    $tot_amount_ord = $tot_amount_ord - $newDiscount;
                    $objOrder->sub_total = number_format($subTotalWithOutIGV,2, '.', '');
                    $objOrder->total = number_format($tot_amount_ord,2, '.', '');
                    $objOrder->save();
                }
                catch(Exception $e){
                    dd($e); 
                }
                /*--- RETORNAMOS LA ORDEN REGISTRADA ---*/
                return $this->SendSuccessResponse(null, array("order_id" => $objOrder->id, 'order'=>$objOrder));
        }catch(\Exception $ex){
            return $this->SendErrorResponse($ex);
        }
    }
    public function GetMailData(Request $request){
        $objOrder = Order::GetById($request["order_id"]);
        $lstOD = OrderDetail::GetByTableId(Order::class,$objOrder->id);
        $objUser = User::GetById($objOrder->user_id);
        $objCurrency = Currency::GetById($objOrder->currency_id);
        $objAddress = Address::GetById($objOrder->shipping_address_id);

        $lstOderDetail = array();
        for($i=0;$i<count($lstOD);$i++){
            $objProduct = Product::FullProductInfoById($lstOD[$i]->product_id,$objCurrency->id);

            if(GenService::GenIntegrated()==1){
                $gen_keys = json_decode($objProduct->gen_keys);
                $price = GenService::GetPriceForItem($gen_keys->item_no,array("default_currency_code"=>$objCurrency->code));
                $stock = GenService::GetStockForItem($gen_keys->item_no,$gen_keys->mfg_ser_lot_no);
                
                if($price!=null){
                    $objProduct->online_price = $price[0];
                    $objProduct->regular_price = $price[1];
                    $objProduct->VISIBLE = 1;
                }else{
                    $objProduct->online_price = "0.00";
                    $objProduct->regular_price = null;
                    $objProduct->VISIBLE = 0;
                }
                $objProduct->stock = intval($stock);
            }else{
                $objProduct->VISIBLE = 1;
            }
            $lstOderDetail[] = array(
                "product"=>$objProduct,
                "cart"=>$lstOD[$i],
            );
        }

        $arrUbications = array();
        $objUbication = Ubication::GetById($objAddress->ubication_id);
        while($objUbication!=null){
            array_push($arrUbications, $objUbication);
            $objUbication = Ubication::GetById($objUbication->root_ubication_id);
        }

        $discount=null;
        if($objOrder->id_discount!=null){
            $discount = Discount::GetById($objOrder->id_discount);
        }

        return $this->SendSuccessResponse(null,array(
            "user"=>$objUser,
            "order"=>$objOrder,
            "order_detail"=>$lstOderDetail,
            "currency"=>$objCurrency,
            "ubication"=>array(
                "address" => $objAddress,
                "cities" => $arrUbications,
            ),
            "discount"=>$discount
        ));
    }
}

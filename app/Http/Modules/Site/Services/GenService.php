<?php
namespace App\Http\Modules\Site\Services;

use App\Http\Models\Database;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\Order;
use App\Http\Models\Database\User;
use App\Http\Models\Database\OrderDetail;
use App\Http\Models\Database\Address;
use App\Http\Models\Database\Currency;
use App\Http\Models\Database\Ubication;
use App\Http\Common\Helpers\StringHelper;

class GenService{
    /******************************************************************************************************************/
    public static function GenIntegrated(){
        $gen=Parameter::GetByCode('is_gen');
        if($gen==null || $gen==''){
            $gen=0;
        }
        return $gen;
    }
    /******************************************************************************************************************/
    /******************************************************************************************************************/
	public static function ReadGenWebservice($tag,$parameters){

        //https://webapp-rest.azurewebsites.net/
		try {
            $cliente = curl_init();
            curl_setopt($cliente, CURLOPT_URL,'https://webapp-rest.azurewebsites.net/'. $tag);
            curl_setopt($cliente, CURLOPT_POST, TRUE);
            curl_setopt($cliente, CURLOPT_POSTFIELDS, json_encode($parameters));
            curl_setopt($cliente, CURLOPT_HEADER, false);
            curl_setopt($cliente, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($cliente, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($cliente, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($cliente, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($cliente, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($cliente, CURLOPT_TIMEOUT, 9999999); //timeout in seconds

            $result = curl_exec($cliente);
            $data = json_decode($result, true);

            /*if($tag == 'api/OrderRegistration'){
                dump(json_encode($parameters));
                dd('https://webapp-rest.azurewebsites.net/'. $tag);
            }*/

            if($data["ResponseCode"]!="0"){
                $data = null;
            }
            return $data;
        }catch(\Exception $ex){
            return $ex;
        }
    }
	
	public static function GetStockForItem($gen_item_no,$mfg_ser_lot_no){

        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
        $post = [
            "Id_enterprise"     =>  $gen_data["Id_enterprise"],
            "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
            "item_no"           =>  $gen_item_no,
            "mfg_ser_lot_no"    =>  $mfg_ser_lot_no,
        ];
        if(strtoupper($mfg_ser_lot_no)=='-1' || strtoupper($mfg_ser_lot_no)=='-'){
            $post = [
                "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                "item_no"           =>  $gen_item_no,
            ];
        }

        $data = GenService::ReadGenWebservice("api/GetStockItem",$post);

        if($data==null) return 0;
        return (array_key_exists("Qty_on_hand",$data)?$data["Qty_on_hand"]:0);
    }
	
	public static function GetPriceForItem($gen_item_no,$currency){
        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
	
        if($currency['default_currency_code']=='PEN'){
            $currency['default_currency_code']='SOL';
        }

        $post = [
            "Id_enterprise"     =>  $gen_data["Id_enterprise"],
            "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
            "item_no"           =>  $gen_item_no,
            "curr_cd"           =>  $currency['default_currency_code'],
        ];

        $data = GenService::ReadGenWebservice("api/GetPrecioItem",$post);
        
        if($data==null) return null;
        if(!array_key_exists("Uni_price",$data)) return null;
        $price = $data["Uni_price"];
        $null_price = null;
        if ($data["Discount_pct"]>0){
            $null_price = $price;
            $price = $price - ($price *$data["Discount_pct"] /100);
			$price = $price;
        }else{
			$price = $price;
		}
        return array($price,($null_price)?$null_price:null);
    }
	
	public static function GetStockItemList($listItems){
        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));

            $post = [
            "Id_enterprise"     =>  $gen_data["Id_enterprise"],
            "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
            "Items" => $listItems,
            ];

            $data = GenService::ReadGenWebservice("api/GetStockItemList", $post);
            if ($data == null) return null;
            return $data["Items"];
    }
	
	public static function GetPriceItemList($items,$currency,$order=null){
        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
		$response = array();
		$listItems = array();
		/*****************************************/
		foreach ($items as $item){
				$gen_keys = json_decode($item->gen_keys);
				$listItems[] = array("item_no" => $gen_keys->item_no);
		}
		/*****************************************/
        
		if($currency['default_currency_code']=='PEN'){
            $currency['default_currency_code']='SOL';
        }

        $post = [
                "Id_enterprise"     =>  $gen_data["Id_enterprise"],
				"Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
				"curr_cd" => $currency['default_currency_code'],
                "Items" => $listItems,
        ];

            $data = GenService::ReadGenWebservice("api/GetPrecioItemList", $post);
     
            if ($data == null) return $response;
			$priceList = array();
           
            foreach($items as $variant){

                $gen_keys = json_decode($variant->gen_keys); 
                $price_array = array();
				$price_array["regular_value"] = null;
				$price_array["online_value"] = "0.00";
                $variant->VISIBLE = 0;
				foreach ($data["Items"] as $item){
					if($gen_keys->item_no==$item["Item_no"]){
						
						$price = $item["Uni_price"];
						$null_price = null;
						if ($item["Discount_pct"]>0){
							$null_price = $price."";
							$price = $price - ($price *$item["Discount_pct"] /100);
							$price = $price."";
						}
						$price_array = array();
						$price_array["regular_value"] = $null_price;
						$price_array["online_value"] = $price."";
                        $variant->VISIBLE = 1;
					}		
				}
                $priceList[] = array('id'=>$variant->product_id,'price'=>$price_array);	
			}
			$response = GenService::OrderBy($items,$priceList,$order);
		return $response;
    }
    public static function GetPriceItemListForProms($items,$currency,$order=null){
        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
		$response = array();
		$listItems = array();
		/*****************************************/
		foreach ($items as $item){
				$gen_keys = json_decode($item->gen_keys);
				$listItems[] = array("item_no" => $gen_keys->item_no);
		}
		/*****************************************/
        
		if($currency['default_currency_code']=='PEN'){
            $currency['default_currency_code']='SOL';
        }

        $post = [
                "Id_enterprise"     =>  $gen_data["Id_enterprise"],
				"Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
				"curr_cd" => $currency['default_currency_code'],
                "Items" => $listItems,
        ];

            $data = GenService::ReadGenWebservice("api/GetPrecioItemList", $post);
     
            if ($data == null) return $response;
			$priceList = array();
            $qt_max = 0;
            foreach($items as $variant){
                if($qt_max<13){
				    foreach ($data["Items"] as $item){
                        if($gen_keys->item_no==$item["Item_no"]){
                            
                            $price = $item["Uni_price"];
                            if($item["Discount_pct"]>0){
                                $null_price = null;
                                    if ($item["Discount_pct"]>0){
                                        $null_price = $price."";
                                        $price = $price - ($price *$item["Discount_pct"] /100);
                                        $price = $price."";
                                    }
                                $price_array = array();
                                $price_array["regular_value"] = $null_price;
                                $price_array["online_value"] = $price."";
                                $variant->VISIBLE = 1;
                                $qt_max++;
                                $priceList[] = array('id'=>$variant->product_id,'price'=>$price_array);	
                            }
                        }		
				    }
                }
			}
			$response = GenService::OrderBy($items,$priceList,$order);
		return $response;
    }
    /* ---------------------------------------------------------------------------------- */
    public static function LockAllCartForUser($user_token,$user_id){
        $lstCart = Cart::GetByUserId($user_id);
        $registros =  array();
        for($i=0;$i<count($lstCart);$i++){
            $gen_response = GenService::LockItemStockForUser($lstCart[$i]['product_id'],$lstCart[$i]['quantity'],$user_token);
            $registros[] = array('product_id' => $lstCart[$i]['product_id'], 'response' => $gen_response);
        }
        return $registros;
    }
    public static function LockItemStockForUser($gen_keys,$quantity,$user_token){
        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));

            $post = [
                "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                "item_no" => $gen_keys->item_no,
                "mfg_ser_lot_no" => $gen_keys->mfg_ser_lot_no,
                "qty_allocated" => $quantity,
                "user_id" => $user_token//Auth::user()->id,
            ];

            if(strtoupper($gen_keys->mfg_ser_lot_no)!='-1' && strtoupper($gen_keys->mfg_ser_lot_no)!='-'){
                $post = [
                    "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                    "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                    "item_no" => $gen_keys->item_no,
                    "qty_allocated" => $quantity,
                    "user_id" => $user_token//Auth::user()->id,
                ];
            }
            $data = GenService::ReadGenWebservice("api/LockItemStock",$post);
            return $data["ResponseCode"];
    }
    public static function UnLockAllStockForEspecificUser($user_id){
        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
		$post = [
            "Id_enterprise"     =>  $gen_data["Id_enterprise"],
            "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
			"user_id" => $user_id,
		];
        
        $data = GenService::ReadGenWebservice("api/UnlockUserStock",$post);
        return array($data);
    }
    public static function UnLockAllStockForUser($user_id){
        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
		$post = [
            "Id_enterprise"     =>  $gen_data["Id_enterprise"],
            "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
            "user_id" => $user_id,
            ];
            
            $data = GenService::ReadGenWebservice("api/UnlockUserStock",$post);
            return array($data);
        
    }
    public static function UnLockItemStockForUserAndItem($product_id,$user_id){
        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
        
            $objProduct = Product::GetById($product_id);
            $gen_keys = json_decode($objProduct["gen_keys"]);
            $post = [
                "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                "item_no" => $gen_keys->item_no,
                "mfg_ser_lot_no" => $gen_keys->mfg_ser_lot_no,
                "user_id" => $user_id,
            ];
            if(strtoupper($gen_keys->mfg_ser_lot_no)=='-1'){
                $post = [
                    "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                    "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                    "item_no" => $gen_keys->item_no,
                    "user_id" => $user_id,
                ];
            }
            
            $data = GenService::ReadGenWebservice("api/UnlockItemStock",$post);
            return array($data);
    }
    public static function GetUserReservedItemQty($product_id,$user_id){
            $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
            $objProduct = Product::GetById($product_id);
            $gen_keys = json_decode($objProduct["gen_keys"]);
            $post = [
                "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                "item_no" => $gen_keys->item_no,
                "mfg_ser_lot_no" => $gen_keys->mfg_ser_lot_no,
                "user_id" => $user_id,

            ];

            if(strtoupper($gen_keys->mfg_ser_lot_no)=='-1'){
                $post = [
                    "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                    "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                    "item_no" => $gen_keys->item_no,
                    "user_id" => $user_id,

                ];
            }

            $data = GenService::ReadGenWebservice("api/CkeckLockItem",$post);
            if ($data == null) return false;
            $ReservedQty = (array_key_exists("Qty_allocated", $data) ? $data["Qty_allocated"] : 0);
            return $ReservedQty;  
    }
    public static function HasUserAValidItemLock($product_id,$quantity,$user_id){
        return (GenService::GetUserReservedItemQty($product_id,$user_id) == $quantity);
    }
    public static function GenRegisterOrder($order_id,$voucher=null){

            $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
            $objOrder = Order::GetById($order_id);
            $objUser = User::GetById($objOrder["user_id"]);
            $lstOrderDetails = OrderDetail::GetByOrderId($order_id);
            $objCurrency = Currency::GetById($objOrder["currency_id"]);

            $payment_code = (array)json_decode(Parameter::GetByCode(Parameter::GetByCode('default_pasarela')));
            
            $logCart=array();

            for($i=0;$i<count($lstOrderDetails);$i++) {
                $objVariant = Product::GetById($lstOrderDetails[$i]['product_id']);
                $gen_keys = json_decode($objVariant->gen_keys);
                $price=GenService::GetPriceForItem($gen_keys->item_no,array("default_currency_code"=>$objCurrency->code));
                
                if($price[0]==null){
                    $price= array();
                    $price[0] = $lstOrderDetails[$i]['price'];
                }
                else{
                    if(strtoupper($gen_keys->mfg_ser_lot_no)!=''){
					$lstitems[] = [
						"item_no"           => $gen_keys->item_no
						,"mfg_ser_lot_no"   => $gen_keys->mfg_ser_lot_no
						,"unit_price"       => $price[0]
						,"qty_to_ship"      => $lstOrderDetails[$i]['quantity']
					];
                    }else{
                        $lstitems[] = [
                            "item_no"           => $gen_keys->item_no
                            ,"unit_price"       => $price[0]
                            ,"qty_to_ship"      => $lstOrderDetails[$i]['quantity']
                        ];
                    }  
                }

                //REGISTRAMOS EL CARRITO EN EL GEN
                $responseCartGen=GenService::LockItemStockForUser($gen_keys,$lstOrderDetails[$i]['quantity'],$objOrder["token"]);
                $logCart[]=array('product_id'=>$lstOrderDetails[$i]['product_id'],'gen'=>$responseCartGen);
				
            }

            $objBillingAddress = Address::GetById($objOrder["billing_address_id"]);
           
            if ($objBillingAddress){
                $billing_address = StringHelper::CompleteStringUbication($objBillingAddress["address"],$objBillingAddress["ubication_id"]);
            }else{
                $billing_address = '-';
            }
            
            $objOrder["shipping_cost"]=($objOrder["shipping_cost"]==null?0:$objOrder["shipping_cost"]);
            
            //$objOrder["total_disccount"]=($objOrder["total_disccount"]==null?0:$objOrder["total_disccount"]);
            
            $tipo_cambio = 1;
            $tipo_moneda = 'ML';
            
            if($objOrder["currency_id"]!=$tipo_cambio){
                $tipo_cambio = 2;
                $tipo_moneda = 'ME';

                $tipoCambio = GenService::GetTipoCambio($objCurrency->gen_code);
                if($tipoCambio==0.0 || $tipoCambio==null){
                    $tipoCambio = 1;
                }

                $objOrder["shipping_cost"] = $objOrder->shipping_cost * $tipoCambio;
            }
            /*
            if(Currency::GetPrincipal()->id!=Services\UserService::GetPreferenceCurrencyId()){
                $objPreferenceCurrency = Currency::GetById(Services\UserService::GetPreferenceCurrencyId());
                $tipo_cambio = GenService::GetTipoCambio($objPreferenceCurrency->gen_code);
                $tipo_moneda = 'ME';
                $objOrder->shipping_cost = $objOrder->shipping_cost * GenService::GetTipoCambio($objPreferenceCurrency->gen_code);
                $objOrder->total_disccount = $objOrder->total_disccount * GenService::GetTipoCambio($objPreferenceCurrency->gen_code);
            }
            */
            //$objPaymentType = Database\Type::GetById($objOrder->payment_type_id);

            $trx = $objOrder["transaction_pay_code"];
            $default_trans = Parameter::GetByCode('init_code_transfer');
            /*$code_pay = "DP";//DP
            
            if(strpos($trx, $default_trans)===false){
                $code_pay = "MP";//DP   
            }*/
            $code_pay = $payment_code['gen_code'];

            $discount_amt = 0;
            $discount_fg = "N";
            if($objOrder["value_discount"]!=null){
                $discount_amt = floatval($objOrder["value_discount"]);
                $discount_fg = "Y";
            }

            $post = [
                "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                "user_def_fld_1" => $objUser["dni"],
                "cus_name" => $objUser["first_name"]." ".$objUser["last_name"],
                "typ_doc_id_snt" => 1, //DNI
                "email_addr" => $objUser["email"], 
                "addr_1" => $billing_address,
                "phone_no" => ($objBillingAddress)?$objBillingAddress["phone"]:'-',
                "payment_type_cd" => $tipo_moneda,//ME / ML
                "User_id" => $objOrder["token"],
                "doc_type" => "I",
                "tax_sched" => 'GVV18', //Siempre vacío según Giovani
                "orig_trx_rt" => $tipo_cambio, //tipo de cambio - aún por definir bien!
                "cost_fg" => ($objOrder["shipping_cost"]==0?"N":"Y"),

                "pay_terms_cd" =>  $code_pay,//StringService::GetString($objPaymentType->description_string_id),
                "card_number" => ($voucher!=null?$voucher:$objOrder->transaction_pay_code),
                "transaction_cd" => ($voucher!=null?$voucher:$objOrder->transaction_pay_code),

                "cost_amt" => $objOrder["shipping_cost"],
                "discount_fg" => $discount_fg,//($objOrder->total_disccount==0?"N":"Y"),
                "discount_amt" => $discount_amt,//abs($objOrder->total_disccount),
                "Items" => $lstitems
            ];
            $data = GenService::ReadGenWebservice("api/OrderRegistration",$post);
            
            if ($data == null) return false;
            if(!array_key_exists("Mensaje", $data)||!array_key_exists("ResponseCode", $data)) return false;
            if($data["ResponseCode"]!=0) return false;

            return $data["Mensaje"]; 
    }
    public static function GenRegisterOrderToRequest($order_id){

            $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
            $objOrder = Order::GetById($order_id);
            $objUser = User::GetById($objOrder["user_id"]);
            $lstOrderDetails = OrderDetail::GetByOrderId($order_id);
            $objCurrency = Currency::GetById($objOrder["currency_id"]);

            for($i=0;$i<count($lstOrderDetails);$i++) {

                $objVariant = Product::GetById($lstOrderDetails[$i]['product_id']);
                $gen_keys = json_decode($objVariant["gen_keys"]);
                $price=GenService::GetPriceForItem($gen_keys->item_no,array("default_currency_code"=>$objCurrency->code));
                /*
                if(Currency::GetPrincipal()->id!=Services\UserService::GetPreferenceCurrencyId()) {
                    $objPrice = Database\Price::GetProductVariantPriceByCurrency($objVariant->id, Currency::GetPrincipal()->id);
                    $price = $objPrice->value;
                }
                */
                $lstitems[] = [
                    "item_no"           => $gen_keys->item_no
                    ,"mfg_ser_lot_no"   => $gen_keys->mfg_ser_lot_no
                    ,"unit_price"       => $price
                    ,"qty_to_ship"      => $lstOrderDetails[$i]['quantity']
                ];

                if(strtoupper($gen_keys->mfg_ser_lot_no)=='-1'){
                    $lstitems[] = [
                        "item_no"           => $gen_keys->item_no
                        ,"unit_price"       => $price
                        ,"qty_to_ship"      => $lstOrderDetails[$i]['quantity']
                    ];
                }

            }
            $objBillingAddress = Address::GetById($objOrder["billing_address_id"]);
            if ($objBillingAddress){
                $ditrict = Ubication::GetById($objBillingAddress["id"]);
                $department = Ubication::GetById($ditrict["id"]);
                $country = Ubication::GetById($department["id"]);
                $billing_address = $objBillingAddress["address"] ." - ". $ditrict["name"]. ", ".$department["name"].", ".$country["name"];
            }else{
                $billing_address = '-';
            }


            $objOrder["shipping_cost"]=($objOrder["shipping_cost"]==null?0:$objOrder["shipping_cost"]);
            $objOrder["total_disccount"]=($objOrder["total_disccount"]==null?0:$objOrder["total_disccount"]);

            $tipo_cambio = 1;
            $tipo_moneda = 'ML';

            /*
            if(Currency::GetPrincipal()->id!=Services\UserService::GetPreferenceCurrencyId()){
                $objPreferenceCurrency = Currency::GetById(Services\UserService::GetPreferenceCurrencyId());
                $tipo_cambio = GenService::GetTipoCambio($objPreferenceCurrency->gen_code);
                $tipo_moneda = 'ME';
                $objOrder->shipping_cost = $objOrder->shipping_cost * GenService::GetTipoCambio($objPreferenceCurrency->gen_code);
                $objOrder->total_disccount = $objOrder->total_disccount * GenService::GetTipoCambio($objPreferenceCurrency->gen_code);
            }
            */
            //$objPaymentType = Database\Type::GetById($objOrder->payment_type_id);

            $post = [
                "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                "user_def_fld_1" => $objUser["identification_code"],
                "cus_name" => $objUser["first_name"]." ".$objUser["last_name"],
                "typ_doc_id_snt" => 1, //DNI
                "email_addr" => $objUser["email"],
                "addr_1" => $billing_address,
                "phone_no" => $objUser["phone"],
                "payment_type_cd" => $tipo_moneda,//ME / ML
                "User_id" => $objUser["id"],
                "doc_type" => "I",
                "tax_sched" => 'GVV18', //Siempre vacío según Giovani
                "orig_trx_rt" => $tipo_cambio, //tipo de cambio - aún por definir bien!
                "cost_fg" => ($objOrder["shipping_cost"]==0?"N":"Y"),

                "pay_terms_cd" => '',//StringService::GetString($objPaymentType->description_string_id),
                "transaction_cd" => ($objOrder["transaction_pay_code"])?$objOrder["transaction_pay_code"]:'-',
                "card_number " => 1,//$objOrder->transaction_pay_code,

                "cost_amt" => $objOrder["shipping_cost"],
                "discount_fg" => ($objOrder["total_disccount"]==0?"N":"Y"),
                "discount_amt" => abs($objOrder["total_disccount"]),
                "Items" => $lstitems
            ];

            $data = GenService::ReadGenWebservice("api/OrderRegistration",$post);
			
            if ($data == null) return false;

            if(!array_key_exists("Mensaje", $data)||!array_key_exists("ResponseCode", $data)) return false;
            if($data["ResponseCode"]!=0) return false;

            return $data["Mensaje"];
    }
    public static function GetTipoCambio($currency_gen_code){
        $gen_data=(array)json_decode(Parameter::GetByCode('gen_data'));
        if(GenService::GenIntegrated()) {
            $post = [
                "Id_enterprise"     =>  $gen_data["Id_enterprise"],
                "Doi_enterprise"    =>  $gen_data["Doi_enterprise"],
                "Curr_cd" => $currency_gen_code,
            ];
            $data = GenService::ReadGenWebservice("api/GetTipoCambio",$post);
            if ($data == null) return null;
            return (array_key_exists("Curr_rt", $data) ? $data["Curr_rt"] : null);
        }else{
            return null;
        }
    } 
    /* ---------------------------------------------------------------------------------- */
    //////////////////////////////////////////////////////////////////////////
	public static function OrderBy($lstItems,$price,$order){
			
			$lstProducts = $price;
			$aux=array();
			$Products = array();
			switch ($order){
				case 2:
					for($j=0;$j<count($lstProducts);$j++){
						$aux[$j]=$lstProducts[$j]['price']['online_value'];
					}
					array_multisort($aux,SORT_ASC, $lstProducts);
					break;
				case 3:
					for($j=0;$j<count($lstProducts);$j++){
						$aux[$j]=$lstProducts[$j]['price']['online_value'];
					}
					array_multisort($aux,SORT_DESC, $lstProducts);
					break;
			}
			
			for($i=0;$i<count($price);$i++){ 
				foreach ($lstItems as $item){
					if($item->product_id==$price[$i]["id"]){

						$item->regular_price = $price[$i]['price']['regular_value'];
						$item->online_price = $price[$i]['price']['online_value'];
						$Products[]=$item;
					}
				}
			}
			
			return $Products;
	} 
	
}

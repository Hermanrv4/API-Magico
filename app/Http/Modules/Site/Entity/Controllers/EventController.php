<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Currency;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\Event;
use App\Http\Models\Database\User;
use App\Http\Models\Database\EventInvitation;
use App\Http\Models\Database\ListProductsEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Common\Helpers\StringHelper;
use App\Http\Modules\Site\Services\GenService;
use Exception;

class EventController extends ApiController{
    public function Get(Request $request){
		// llevar todos los datos
		$data = Event::all();
		if(isset($request['id_user']) && $request['id_user']!="" || $request['id_user']!=null){
			$data=Event::where('user_id', "=", $request['id_user'])->get();
		}
		if(isset($request['event_id']) && $request['event_id']!="" || $request['event_id']!=null){
			return $this->SendSuccessResponse(null, Event::GetById($request['event_id']));
		}
		if(isset($request['code_event']) && $request['code_event']!="" || $request['code_event']!=null){
			$data=Event::where((new Event())->getTable().'.token',"=", $request['code_event'])->get();
			return $this->SendSuccessResponse(null, $data);
		}
		if(count($data)>0){
			for($i=0;$i<count($data);$i++){
				$user = User::GetById($data[$i]->user_id);
				$data[$i]->first_name = $user->first_name;
				$data[$i]->last_name = $user->last_name;
			}
		}
        return $this->SendSuccessResponse(null,$data);
    }
	
	public function GetByEventByToken(Request $request){
		
		if($request["token"]==null){
			return $this->SendSuccessResponse(null,array());
		}else{
			$Event = Event::GetByToken($request["token"]);
			return $this->SendSuccessResponse(null,$Event);
		}
    }
	
	public function GetProductsByEvent(Request $request){
		$LstProducts=ListProductsEvent::GetByEventId($request["id"]);
		
		if(!isset($request["currency_id"])){
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }
	
        $data = Product::GetByFilters(
            str_replace(Parameter::GetByCode('db_query_union'),",",$request["categories"])
			,isset($request["order_by"])?$request["order_by"]:1
            ,isset($request["search"])?$request["search"]:null
            ,isset($request["filters"])?$request["filters"]:null
            ,$objCurrency->id
            ,isset($request["min_price"])?$request["min_price"]:null
            ,isset($request["max_price"])?$request["max_price"]:null
            ,isset($request["discounted"])?$request["discounted"]:null
            ,isset($request["page_num"])?$request["page_num"]:null
            ,isset($request["page_qty"])?$request["page_qty"]:Parameter::GetByCode('product_catalogue_quantity',null)
        );
		$new_data = $data;
		if(GenService::GenIntegrated()==1){
			$new_data =GenService::GetPriceItemList($data,array("default_currency_code"=>$objCurrency->code),$request["order_by"]);
		}
		$products = array();
		$cont = 0;
		for($j=0;$j<count($new_data);$j++){
			for($k=0;$k<count($LstProducts);$k++){
				if($new_data[$j]->product_id==$LstProducts[$k]->product_id){
					$cont ++;
					$new_data[$j]->STOCK_MAX = $LstProducts[$k]->quantity;
					$new_data[$j]->ACUMULATED = $LstProducts[$k]->quantity_acumulated;
					if($new_data[$j]->STOCK_MAX>$new_data[$j]->ACUMULATED){
						$products[]=$new_data[$j];
					}
				}
			}
		}
		return $this->SendSuccessResponse(null,$products);
		
	}
	
    public function GetByEventById(Request $request){
		
		if($request["user_id"]==null){
			return $this->SendSuccessResponse(null,array());
		}else{
			$Event = Event::GetByIdUserId($request["event_id"],$request["user_id"]);
			/* return $this->SendSuccessResponse(null,$lstEvents); */
			return $this->SendSuccessResponse(null,$Event);
		}
    }
	public function AddProduct(Request $request){
		
		if($request["event_id"]!=null && $request["product_id"]!=null && $request["quantity"]!=null && $request["quantity"]!=0){
			
			$LstProdEvent = new ListProductsEvent();
			$LstProdEvent->event_id = $request["event_id"];
			$LstProdEvent->product_id = $request["product_id"];
			$LstProdEvent->quantity = $request["quantity"];
			$LstProdEvent->save();
			
			return $this->SendSuccessResponse(null,$LstProdEvent);
			
		}else{
			return $this->SendErrorResponse(null,array());
		}
		
	}
	
	public function DeleteEvent(Request $request){
		$event_data = Event::GetById($request["id"]);
		$id = $event_data->id;
		
		$res = EventInvitation::DeleteByEventId($id);
		$res = ListProductsEvent::DeleteByEventId($id);
		$res = Event::DeleteById($id);
		
		return $this->SendSuccessResponse(null,$res);
		
	}
	public function UpdateEvent(Request $request){
		
		/* if($request["user_id"]!=null && $request["name"]!=null && $request["description"]!=null && $request["address_id"]!=null && $request["list_products"]!=null && $request["list_invites"]!=null && $request["end_at"]!=null && $request["address_id"]!=null && $request["gratitude"]!=null){
		} */
		$objEvent=Event::GetById($request['id_event']);
		$objEvent->name=$request['name'];
		$objEvent->description=$request['description'];
		$objEvent->banner=$request['banner']??'default.png';
		$objEvent->banner_gratitude=$request["banner_gratitude"]??'default.png';
		$objEvent->end_at=$request['end_at'];
		$objEvent->start_event=$request['event_at'];
		$objEvent->address_id=$request['address_id'];
		$objEvent->address=$request['address'];
		$objEvent->gratitude=$request['gratitude'];
		$objEvent->save();
		/* $lstInvites=json_decode($request['list_invites'], true); */
		// ingresar datos invitados
		//deberemos obtener el id del invitado
		/* $lstInvites=json_decode($request['list_invites'], true);
		if(count($lstInvites)>0){
			for($item=0; $item<count($lstInvites); $item++){
				// buscamos si existe o si creamos
				if($lstInvites[$item]['id_invited']!=null || $lstInvites[$item]['id_invited']!=""){
					if( EventInvitation::where( (new EventInvitation)->getTable().'.id', "=", $lstInvites[$item]['id_invited'])->exists()){
						$objInvited=EventInvitation::GetById($lstInvites[$item]['id_invited']);
						$objInvited->email=$lstInvites[$item]['email'];
						$objInvited->save();
					}else{
						$objInvited=new EventInvitation();
						$objInvited->email=$lstInvites[$item]['email'];
						$objInvited->event_id=$request['id_event'];
						$objInvited->save();
					}
				}else{
					$objInvited=new EventInvitation();
					$objInvited->email=$lstInvites[$item]['email'];
					$objInvited->event_id=$request['id_event'];
					$objInvited->save();
				}
			}
		} */
		// ahora recorremos la lista de productos
		$lstproducts=json_decode($request['list_products'], true);
		try{
			if(count($lstproducts)>0){
				for($item=0; $item<count($lstproducts); $item++){
					if(ListProductsEvent::where( (new ListProductsEvent)->getTable().'.event_id', "=", $request['id_event'])->where( (new ListProductsEvent)->getTable().'.product_id', "=", $lstproducts[$item]['id_product'] )->exists()){
						if($lstproducts[$item]['quantity']==0 || $lstproducts[$item]['quantity']=="0"){
							/* $objListProd=ListProductsEvent::GetById($lstproducts[$item]['id']); */
							$objListProd=ListProductsEvent::where( (new ListProductsEvent)->getTable().'.event_id', "=", $request['id_event'])->where( (new ListProductsEvent)->getTable().'.product_id', "=", $lstproducts[$item]['id_product'] );
							$objListProd->delete();
						}else{
							$objProd=ListProductsEvent::where( (new ListProductsEvent)->getTable().'.event_id', "=", $request['id_event'])->where( (new ListProductsEvent)->getTable().'.product_id', "=", $lstproducts[$item]['id_product'])->get();
							$objListProd=ListProductsEvent::GetById($objProd[0]['id']);
							$objListProd->quantity=$lstproducts[$item]['quantity'];
							$objListProd->save();
						}
					}else{
						$objnewProd=new ListProductsEvent();
						$objnewProd->event_id=$request['id_event'];
						$objnewProd->product_id=$lstproducts[$item]['id_product'];
						$objnewProd->quantity=$lstproducts[$item]['quantity'];
						$objnewProd->quantity_acumulated=0;
						$objnewProd->save();
					}
				}
			}
		}catch(Exception $e){
			/* dd($e); */
		}
		/* $result = array("event_id"=>$objEvent->id,"token"=>$objEvent->token); */
		return $this->SendSuccessResponse(null,$objEvent);
	}
	public function AddEvent(Request $request){
		$lstproducts = json_decode($request["list_products"]);
		
		if($request["user_id"]!=null && $request["name"]!=null && $request["description"]!=null && $request["address_id"]!=null && $request["list_products"]!=null && $request["list_invites"]!=null && $request["end_at"]!=null && $request["address_id"]!=null && $request["gratitude"]!=null){
		
			/* $token = StringHelper::GetTokenForNewEvent(); */
			// obtener los datos del id del usuario
			$token=(User::GetById($request['user_id']))->first_name;
			$token=strtoupper(StringHelper::removeAccents($token));
			
			$event = new Event();
			$event->user_id = $request["user_id"];
			$event->name = $request["name"];
			$event->description = $request["description"];
			$event->banner = "default.png";
			$event->banner_gratitude = "default.png";
			
			if($request["banner"]!=null && $request["banner"]!=''){
				$event->banner = $request["banner"];
			}
			
			
			if($request["banner_gratitude"]!=null && $request["banner_gratitude"]!=''){
				$event->banner_gratitude = $request["banner_gratitude"];
			}
			/* $event->token = $token; */
			$event->end_at = $request["end_at"];
			$event->start_event = $request["event_at"];
			$event->address_id = $request["address_id"];
			$event->address= $request["address"];
			$event->gratitude = $request["gratitude"];
			$event->save();
			/* $token=str_replace(" ","-",$token).'-'.$event->id; */
			$token=explode('-', str_replace(' ', '-', $token));
			$token=$token[0].'-'.$event->id;
			$objEvent=DB::select('update events set token = ? where id = ? ', [$token, $event->id]);
			
			/* $event_data = Event::GetByToken($token);
			if($request['list_invites']!=null){
				$lst_invites = json_decode($request["list_invites"]);
				if(count($lst_invites)>0 || $lst_invites!=null){
					for($i=0;$i<count($lst_invites);$i++){
						$data = $lst_invites[$i];
						$invite = new EventInvitation();
						if(strpos($data->email, "@")!=false && $data->email!=null){
							$invite->event_id = $event_data->id;
							$invite->email = $data->email;
							$invite->save();
						}	
					}
				}
			} */

			$event_data = Event::GetByToken($token);
			$lst_products = json_decode($request["list_products"]);
			for($j=0;$j<count($lst_products);$j++){
				$data = $lst_products[$j];
				$product = new ListProductsEvent();
				
				if($data->id_product!=null && $data->quantity!=null && intval($data->quantity)>0){
					$product->event_id =  $event_data->id;
					$product->product_id =  $data->id_product;
					$product->quantity =  $data->quantity;
					$product->quantity_acumulated =  0;
					$product->save();
				}
			}
		
			$result = array("event_id"=>$event_data->id,"token"=>$token);
		
			return $this->SendSuccessResponse(null,$result);
			
		}else{
			return $this->SendErrorResponse(null,array());		
		}
	}
	public function GetProductEvent(Request $request){
        return $this->SendSuccessResponse(null, ListProductsEvent::where((new ListProductsEvent)->getTable().'.event_id',"=", $request['id_event'])->get());
    }
}

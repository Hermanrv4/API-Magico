<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\ListProductsEvent;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationListProductsEvent;

class ListProducstEventController extends ApiController{

    public function Get(Request $request){
        if (isset($request["list_product_event_id"]) && $request['list_product_event_id']!='') {
            return $this->SendSuccessResponse(null,ListProductsEvent::GetById($request["list_product_event_id"]));
        }else if (isset($request["event_id"]) && $request['event_id']!='') {
            return $this->SendSuccessResponse(null,ListProductsEvent::GetByEventId($request["event_id"]));
        }else if(isset($request['event_id_list']) && isset($request['product_event_id'])){
            return $this->SendSuccessResponse(null, ListProductsEvent::GetByProdEvent($request['event_id_list'], $request['product_event_id']));
        }else{
            return $this->SendSuccessResponse(null,ListProductsEvent::all());
        }
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationListProductsEvent::ListProductsEventRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objListProductsEvent = new ListProductsEvent();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objListProductsEvent = ListProductsEvent::GetById($request['id']);
                $objListProductsEvent->event_id = $request['event_id'];
                $objListProductsEvent->product_id = $request['product_id'];
                $objListProductsEvent->quantity = $request['quantity'];
                $objListProductsEvent->quantity_acumulated = $request['quantity_acumulated'];
                $objListProductsEvent->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objListProductsEvent));
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
            $validator = ValidationListProductsEvent::ListProductsEventDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objListProductsEvent = ListProductsEvent::GetById($request["id"]);
                $objListProductsEvent->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objListProductsEvent));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse($ex->getMessage());
        }
    }
}

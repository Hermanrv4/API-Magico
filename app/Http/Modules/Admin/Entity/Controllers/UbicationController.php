<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Ubication;
use App\Http\Models\Database\Address;
use App\Http\Models\Database\ShippingPrice;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationUbication;

class UbicationController extends ApiController{

    public function Get(Request $request){
        if (isset($request['ubication_id'])) {
            return $this->SendSuccessResponse(null,Ubication::GetById($request['ubication_id']));
        }else{
            return $this->SendSuccessResponse(null,Ubication::all());
        }
    }

    public function GetByRoot(Request $request)
    {
        if($request["root_ubication_id"]==Parameter::GetByCode('default_id')){
            $ubications = Ubication::GetRootParents();
            return $this->SendSuccessResponse(null,($ubications==null?array():array($ubications)));
        }else{
            $ubications = Ubication::GetChildsByRoot($request["root_ubication_id"]);
            return $this->SendSuccessResponse(null,($ubications==null?array():array($ubications)));
        }
    }    
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationUbication::UbicationRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objUbication = new Ubication();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objUbication = Ubication::GetById($request['id']);
                if($request["root_ubication_id"]==Parameter::GetByCode("default_id")){ $objUbication->root_ubication_id = null;}
                else{$objUbication->root_ubication_id = $request["root_ubication_id"];}
                $objUbication->code = $request['code'];
                $objUbication->name = $this->LocalizationArray($request['name']);
                $objUbication->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objUbication));
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
            $validator = ValidationUbication::UbicationDelete($request,$msg_validation);
            $validator->after(function($validator) use ($request,$msg_validation){
                if(count(Ubication::GetChildsByRoot($request['id']))>0) $validator->errors()->add('form',trans($msg_validation.'form.exist_ubications'));
                if (count(Address::GetByTableId(Ubication::class,$request['id']))>0) $validator->errors()->add('form',trans($msg_validation.'form.exist_address'));
                if (count(ShippingPrice::GetByTableId(Ubication::class,$request['id']))>0) $validator->errors()->add('form',trans($msg_validation.'form.exist_shippingprice'));
            });
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objUbication = Ubication::GetById($request['id']);
                $objUbication->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objUbication));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}

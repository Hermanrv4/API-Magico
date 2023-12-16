<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Address;
use App\Http\Models\Database\Order;
use App\Http\Modules\Admin\Services\ValidationAddress;

class AddressController extends ApiController{
    public function Get(Request $request){
        if (isset($request["address_id"])) {
            return $this->SendSuccessResponse(null,Address::GetById($request["address_id"]));
        }if (isset($request["user_id"])) {
            return $this->SendSuccessResponse(null,Address::GetByUserId($request["user_id"]));
        }else{
            return $this->SendSuccessResponse(null,Address::all());
        }
    }

    public function Register(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationAddress::AddressRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objAddress = new Address();
                $is_update = $request['id']!=Parameter::GetByCode('default_id');
                if ($is_update) $objAddress = Address::GetById($request['id']);
                $objAddress->user_id = $request['user_id'];
                //$objAddress->wish_list_id = $request->input('wish_list_id',null);
                $objAddress->wish_list_id = $request['wish_list_id'];
                $objAddress->ubication_id = $request['ubication_id'];
                $objAddress->address = $request['address'];
                $objAddress->phone = $request['phone'];
                $objAddress->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objAddress));
            }
        } catch (\Exception $ex) {
            if (config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function Delete(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationAddress::AddressDelete($request,$msg_validation);
            $validator->after(function($validator) use ($request,$msg_validation){                
                if(count(Order::GetByTableId(Address::class,$request['id'],'billing'))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_billing_address'));
                if(count(Order::GetByTableId(Address::class,$request['id'],'shipping'))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_shipping_address')); 
            });
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objAddress = Address::GetById($request['id']);
                $objAddress->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objAddress));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}
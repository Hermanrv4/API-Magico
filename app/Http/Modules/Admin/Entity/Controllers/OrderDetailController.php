<?php

namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationOrderDetail;

class OrderDetailController extends ApiController{

    public function Get(Request $request){
        if (isset($request["order_detail_id"])) {
            return $this->SendSuccessResponse(null,OrderDetail::GetById($request["order_detail_id"]));
        }if(isset($request['order_id'])){
            return $this->SendSuccessResponse(null,OrderDetail::GetByOrderId($request['order_id']));
        }
        else{
            return $this->SendSuccessResponse(null,OrderDetail::all());
        }
    }

    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationOrderDetail::OrderDetailRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objOrderDetail = new OrderDetail();                
                $is_update = $request["id"]!=Parameter::GetByCode('default_id');
                if($is_update) $objOrderDetail = OrderDetail::GetById($request['id']);
                $objOrderDetail->order_id = $request['order_id'];
                $objOrderDetail->product_id = $request['product_id'];
                $objOrderDetail->quantity = $request['quantity'];
                $objOrderDetail->price = $request['price'];
                $objOrderDetail->observations = $request['observations'];
                $objOrderDetail->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objOrderDetail));
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
            $validator = ValidationOrderDetail::OrderDetailDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objOrderDetail = OrderDetail::GetById($request['id']);
                $objOrderDetail->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objOrderDetail));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

}
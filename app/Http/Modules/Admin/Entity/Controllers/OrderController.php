<?php

namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\Order;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationOrder;
use App\Http\Modules\Site\Services\GenService;
use App\Http\Modules\Site\Entity\Controllers\OrderController as OrderSite;

class OrderController extends ApiController{

    public function Get(Request $request){
        
        if (isset($request["order_id"])) {
            return $this->SendSuccessResponse(null,Order::GetById($request["order_id"]));
        }
        if (isset($request['user_id'])) {
            if ($request['user_id'] == -1) {
                return $this->SendSuccessResponse(null,Order::all());
            }
            return $this->SendSuccessResponse(null,Order::GetByUserId($request['user_id']));
        }
        else{
            return $this->SendSuccessResponse(null,Order::all());
        }
    }
    public function GetByFilters(Request $request)
    {
        return $this->SendSuccessResponse(null,Order::GetByFilters($request["fec_emision"],$request['currency']));
    }
    public function GetAllOrderBilled(Request $request)
    {
        if(isset($request["dni"]) && isset($request["serie"]) && isset($request["correlative"]) && isset($request["total"]) && isset($request['fec_emision'])){
            return $this->SendSuccessResponse(null,Order::GetByFieldsOrder($request["dni"],$request["serie"],$request["correlative"],$request["total"],$request['fec_emision']));
        }
        if(isset($request["order_id"])){
            return $this->SendSuccessResponse(null,Order::GetOrderBilledByOrderId($request["order_id"])); 
        }
        if (isset($request['fec_emision'])||isset($request['currency'])){
            
        }
        return $this->SendSuccessResponse(null,Order::GetAllOrderBilled());
    }
    public function ChangeStatus(Request $request)
    {
        try {
            $msg_validation=null;
            $validator=ValidationOrder::ChangeStatus($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objOrder = Order::GetById($request['id']);
                $objOrder->status_type_id = $request['status_type_id'];
                $objOrder->save();
                $action_gen="";
                if($request["is_gen"] == 1){
                    $action_gen=OrderSite::UpdateStatusOrder($objOrder->token, $request["status"]);
                }
                return $this->SendSuccessResponse(null, array('result'=>$objOrder) );
            }
        } catch (\Throwable $th) {
            if(config('env.app_debug'))return $this->SendErrorResponse($th->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function GetByProductAndDate(Request $request)
    {
        return $this->SendSuccessResponse(null,Order::withoutGlobalScopes()->GetByProductAndDate($request['product_id'],$request['date'])->get());
    }
    
    public function GetCustomerByDateRange(Request $request)
    {
        /* return $this->SendSuccessResponse(null,Order::withoutGlobalScopes()->CustomerByDate($request['date_start'],$request['date_end'])->get()); */
        return $this->SendSuccessResponse(null, Order::withoutGlobalScopes()->CustomerByDateName($request['date_start'],$request['date_end'])->get());
    }
    public function GetOrderStatusDate(Request $request){
        return $this->SendSuccessResponse(null, Order::withoutGlobalScopes()->GetOrderStatusDate($request['date_start'], $request['date_end'])->get());
    }
    public function GetOrderStatusOfDate(Request $request){
        if($request["option"]=="detail"){
            return $this->SendSuccessResponse(null, Order::withoutGlobalScopes()->GetOrderForStatusOfDate($request["date_start"], $request["date_end"], $request["id_status"])->get());
        }else{
            return $this->SendSuccessResponse(null, Order::withoutGlobalScopes()->GetOrderStatusOfDate($request["date_start"], $request["date_end"], $request["id_status"])->get());
        }
    }
    public function GetOrderOfDate(Request $request){
        return $this->SendSuccessResponse(null, Order::withoutGlobalScopes()->GetOrderOfDate($request["date_start"], $request["date_end"])->get());
    }
}
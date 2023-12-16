<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\ElectronicBillingSale;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationElectronicBillingSale;

class ElectronicBillingSaleController extends ApiController{

    public function Get(Request $request){
        if (isset($request["electronic_billing_id"])) {
            return $this->SendSuccessResponse(null,ElectronicBillingSale::GetById($request["electronic_billing_id"]));
        }
        if (isset($request["order_id"])) {
            return $this->SendSuccessResponse(null,ElectronicBillingSale::GetAllByOrderId($request["order_id"]));
        }
        else{
            return $this->SendSuccessResponse(null,ElectronicBillingSale::all());
        }
    }
    public function ExistOrder(Request $request){
        $r='false';
        if (ElectronicBillingSale::GetExistOrder($request['order_id'])) {
            $r = 'true';
            return $this->SendSuccessResponse('true');
        }
        return $this->SendSuccessResponse('false');
    }
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationElectronicBillingSale::ElectronicBillingSaleRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse('0',$validator->errors());
            }else {
                $objElectronicBillingSale = new ElectronicBillingSale();
                $objElectronicBillingSale->serie = $request['serie'];
                $objElectronicBillingSale->correlative = $request['correlative'];
                $objElectronicBillingSale->order_id = $request['order_id'];
                $objElectronicBillingSale->status = $request['status'];
                $objElectronicBillingSale->save();
                return $this->SendSuccessResponse('1',true);
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
    public function Voided(Request $request)
    {
        try {
            $msg_validation = null;
            $validator = ValidationElectronicBillingSale::ElectronicBillingSaleVoided($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse('0',$validator->errors());
            }else {
                $objElectronicBillingSale = ElectronicBillingSale::GetByOrderId($request['order_id']);
                $objElectronicBillingSale->is_voided = 1;
                $objElectronicBillingSale->save();
                return $this->SendSuccessResponse('Actualizado',array('result'=>$objElectronicBillingSale));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
    public function GetSaleCategoriesDate(Request $request){
        return $this->SendSuccessResponse('end', ElectronicBillingSale::withoutGlobalScopes()->GetSaleCategoriesDate($request['fec_date_start'], $request['fec_date_end'])->get());
    }
}
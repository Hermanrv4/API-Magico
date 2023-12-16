<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\ShippingPrice;
use App\Http\Models\Database\Ubication;
use App\Http\Models\Database\Parameter;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationShippingPrice;

class ShippingPriceController extends ApiController{
    
    public function Get(Request $request)
    {
        if (isset($request['shipping_price_id'])) {
            return $this->SendSuccessResponse(null,ShippingPrice::GetById($request['shipping_price_id']));
        }
        if (isset($request['ubication_id'])) {
            return $this->SendSuccessResponse(null,ShippingPrice::GetByUbicationId($request['ubication_id']));
        }
        if (isset($request['currency_id'])) {
            return $this->SendSuccessResponse(null,ShippingPrice::GetByCurrencyId($request['currency_id']));
        }
        else{
            return $this->SendSuccessResponse(null,ShippingPrice::all());
        }
    }

    public function Register(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationShippingPrice::ShippingPriceRegister($request,$msg_validation);
            /* $validator->after(function($validator) use ($request,$msg_validation){
                if(count(array(ShippingPrice::GetByUbicationIdAndCurencyId($request['ubication_id'],$request['currency_id'])))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_shipping_price'));
            }); */
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objShippingprice = new ShippingPrice();
                $is_update = $request['id']!=Parameter::GetByCode('default_id');
                if ($is_update) $objShippingprice = ShippingPrice::GetById($request['id']);
                $objShippingprice->ubication_id = $request['ubication_id'];
                $objShippingprice->currency_id = $request['currency_id'];
                $objShippingprice->price = $request['price'];
                $objShippingprice->min_days = $request['min_days'];
                $objShippingprice->is_static = $request['is_static'];
                $objShippingprice->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objShippingprice));
            }
        } catch (\Exception $ex) {
            if (config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function UpdateFullUbications(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationShippingPrice::ShippingPriceUpdate($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $ubications = Ubication::GetFullChildsById($request['ubication_id']);
                ShippingPrice::updateOrCreate(['currency_id'=>$request['currency_id'],'ubication_id'=>$request['ubication_id']],['price'=>$request['price'],'min_days'=>$request['min_days'],'is_static'=>$request['is_static']]);
                for ($i=0; $i < count($ubications); $i++) { 
                    ShippingPrice::updateOrCreate(['currency_id'=>$request['currency_id'],'ubication_id'=>$ubications[$i]],['price'=>$request['price'],'min_days'=>$request['min_days'],'is_static'=>$request['is_static']]);
                }
                //ShippingPrice::where('currency_id',$request['currency_id'])->whereIn('ubication_id',$ubications)->update(['price'=>$request['price'],'min_days'=>$request['min_days'],'is_static'=>$request['is_static']]);
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>''));
            }
        } catch (\Exception $ex) {
            if (config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse($ex->getMessage());
        }
    }

    public function Delete(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationShippingPrice::ShippingPriceDelete($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                //$objShippingprice = ShippingPrice::GetById($request['id']);
                //$objShippingprice->delete();
                $objShippingprice = ShippingPrice::DeleteById($request['id']);
                return $this->SendSuccessResponse(null,array('result'=>$objShippingprice));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}
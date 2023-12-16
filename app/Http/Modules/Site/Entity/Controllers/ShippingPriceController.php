<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Currency;
use App\Http\Models\Database\OrderDetail;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\ProductGroup;
use App\Http\Models\Database\ProductPrice;
use App\Http\Models\Database\Provider;
use App\Http\Models\Database\ShippingPrice;
use App\Http\Models\Database\Ubication;
use Illuminate\Http\Request;

class ShippingPriceController extends ApiController{
    public function Get(Request $request){
        if(!isset($request["currency_id"])){
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }
        
        $is_complete = true;
        $price = null;
        $min_days = 0;
        $is_static = 0;
        try {
            $objSP = ShippingPrice::GetByUbicationIdAndCurencyId($request["ubication_id"], $objCurrency->id);
            $price = $objSP->price;
            $is_static = $objSP->is_static;
            if($objSP->min_days>$min_days) $min_days = $objSP->min_days;
        }catch(\Exception $ex){
            $is_complete = false;
        }

        $min_date = DateHelper::AddDaysToDate(DateHelper::GetNow(),$min_days,false);

        return $this->SendSuccessResponse(null,array("min_date"=>$min_date->format('Ymd'),"price"=>$price,"is_complete"=>$is_complete,"is_static"=>$is_static));
    }
}

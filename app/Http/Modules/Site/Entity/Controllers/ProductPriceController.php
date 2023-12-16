<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Services\EntityService;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Currency;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\ProductGroup;
use App\Http\Models\Database\ProductPrice;
use App\Http\Models\Database\Specification;
use Illuminate\Http\Request;
use App\Http\Modules\Site\Services\GenService;

class ProductPriceController extends ApiController{
    public function GetByProduct(Request $request){
        if(!isset($request["currency_id"])){
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }
		
		$data = ProductPrice::GetByProductIdAndCurrencyId($request["product_id"],$objCurrency->id);
		if(GenService::GenIntegrated()==1){
            
			$product = Product::GetProductById($request["product_id"]);
			$gen_keys = json_decode($product->gen_keys);
			$price = GenService::GetPriceForItem($gen_keys->item_no,array("default_currency_code"=>$objCurrency->code));
			$data->online_price = $price[0]."";
			$data->regular_price = $price[1]."";
			
		}

        return $this->SendSuccessResponse(null,$data);
    }
}

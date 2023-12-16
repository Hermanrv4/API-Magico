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

class ProductController extends ApiController{
    public function GetAll(Request $request){
        if(isset($request['option']) && $request['option']!=null && $request['option']!=""){
            $data=Product::where( (new Product)->getTable().'.is_active', '=', '1' )->get();
            return $this->SendSuccessResponse(null, $data);
        }else{
            return $this->SendSuccessResponse(null,Product::all());
        }
    }
    public function GetLatest(Request $request){
        if(!isset($request["currency_id"])){
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }
		
		$limited = 0;
		if(isset($request["limited"])){
            $limited = $request["limited"];
        }
		
        $data = Product::GetLatest($objCurrency->id,$limited);
		if(GenService::GenIntegrated()==1){
			$new_data =GenService::GetPriceItemList($data,array("default_currency_code"=>$objCurrency->gen_code),$request["order_by"]);
            $data = $new_data;
		}

        return $this->SendSuccessResponse(null,$data);
    }
	public function ProductFacebook(Request $request){
		if(!isset($request["currency_id"])){
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }
		
		$limited = 0;
		if(isset($request["limited"])){
            $limited = $request["limited"];
        }
		
        $data = Product::GetAll($objCurrency->id,$limited);
		if(GenService::GenIntegrated()==1){
			$new_data =GenService::GetPriceItemList($data,array("default_currency_code"=>$objCurrency->gen_code),$request["order_by"]);
            $data = $new_data;
		}

        return $this->SendSuccessResponse(null,$data);
	}
    public function GetMoreSells(Request $request){
        if(!isset($request["currency_id"])){ 
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }
        $data = Product::GetMoreSells($objCurrency->id);
		if(GenService::GenIntegrated()==1){
			$new_data =GenService::GetPriceItemList($data,array("default_currency_code"=>$objCurrency->gen_code),$request["order_by"]);
            $data = $new_data;
		}

        return $this->SendSuccessResponse(null,$data);
    }

    public function GetProms(Request $request){
        if(!isset($request["currency_id"])){
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }

        if(!isset($request["order_by"])){
            $request["order_by"] = 1;
        }
        $new_data = null;
        if(GenService::GenIntegrated()==1){
            $all_categories = Parameter::GetByCode('all_categories');
            $data = Product::GetByFilters(
                str_replace(Parameter::GetByCode('db_query_union'),",",$all_categories)
                ,$request["order_by"]
                ,isset($request["search"])?$request["search"]:null
                ,isset($request["filters"])?$request["filters"]:null
                ,$objCurrency->id
                ,isset($request["min_price"])?$request["min_price"]:null
                ,isset($request["max_price"])?$request["max_price"]:null
                ,isset($request["discounted"])?$request["discounted"]:null
                ,isset($request["page_num"])?$request["page_num"]:null
                ,isset($request["page_qty"])?$request["page_qty"]:Parameter::GetByCode('product_catalogue_quantity',null)
            );
			$new_data =GenService::GetPriceItemListForProms($data,array("default_currency_code"=>$objCurrency->gen_code),$request["order_by"]);
		}else{
            $data = Product::GetProms($objCurrency->id);
            $new_data = $data;
        }
        return $this->SendSuccessResponse(null,$new_data);
    }
    public function GetMostSimilars(Request $request){

            if(!isset($request["currency_id"])){
                $objCurrency = Currency::GetByCode($request["currency_code"]);
            }else{
                $objCurrency = Currency::GetById($request["currency_id"]);
            }
           
            $data = Product::GetMostSimiliars($objCurrency->id,$request["id"],$request["specfications"],$request["category_id"]);
            if(GenService::GenIntegrated()==1){
                $new_data = GenService::GetPriceItemList($data,array("default_currency_code"=>$objCurrency->gen_code),$request["order_by"]);
                $data = $new_data;
            }

            return $this->SendSuccessResponse(null, $data);

    }
    //GetPriceItemListForProms
    public static function GetProductData($id,$currency_id){

        $product_info = null;
        $objCurrency = Currency::GetById($currency_id);
        $objProduct = Product::GetById($id);
        $product_info = Product::FullProductInfoById($objProduct->id,$objCurrency->id);
		if(GenService::GenIntegrated()==1){
            $gen_keys = json_decode($objProduct["gen_keys"]);
			$price = GenService::GetPriceForItem($gen_keys->item_no,array("default_currency_code"=>$objCurrency->gen_code));
			$stock = GenService::GetStockForItem($gen_keys->item_no,$gen_keys->mfg_ser_lot_no);
            if($price!=null){
                $product_info->online_price = $price[0];
                $product_info->regular_price = $price[1];
                $product_info->VISIBLE = 1;
            }else{
                $product_info->online_price = "0.00";
                $product_info->regular_price = null;
                $product_info->VISIBLE = 0;
            }
            $product_info->stock = intval($stock);
		}
        return $product_info;
    }
    public function GetSimilars(Request $request){
        try {
            if(!isset($request["currency_id"])){
                $objCurrency = Currency::GetByCode($request["currency_code"]);
            }else{
                $objCurrency = Currency::GetById($request["currency_id"]);
            }
            $objProductGroup = ProductGroup::GetByCode($request["product_group_code"]);
            $objExcludedProduct = Product::GetByUrlCode($request["excluded_product_url_code"]);
            $data = Product::GetSimilars($objProductGroup->id, $objExcludedProduct->id, $request["filters"], $objCurrency->id);
            if(count($data)==0 && isset($request["sel_specification"])){
                $data = Product::GetSimilars($objProductGroup->id, $objExcludedProduct->id, $request["sel_specification"], $objCurrency->id);
            }
            
            if(GenService::GenIntegrated()==1){
                $new_data =GenService::GetPriceItemList($data,array("default_currency_code"=>$objCurrency->gen_code),$request["order_by"]);
                $data = $new_data;
            }

            return $this->SendSuccessResponse(null, $data);
        }catch (\Exception $ex){}
    }
    public function GetById(Request $request){
        if(!isset($request["currency_id"])){
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }
        $objProduct = Product::GetById($request["product_id"]);
        $product_info = Product::FullProductInfoById($objProduct->id,$objCurrency->id);
		if(GenService::GenIntegrated()==1){
            $gen_keys = json_decode($objProduct["gen_keys"]);
			$price = GenService::GetPriceForItem($gen_keys->item_no,array("default_currency_code"=>$objCurrency->gen_code));
			$stock = GenService::GetStockForItem($gen_keys->item_no,$gen_keys->mfg_ser_lot_no);
            if($price!=null){
                $product_info->online_price = $price[0];
                $product_info->regular_price = $price[1];
                $product_info->VISIBLE = 1;
            }else{
                $product_info->online_price = "0.00";
                $product_info->regular_price = null;
                $product_info->VISIBLE = 0;
            }
            $product_info->stock = intval($stock);
		}
		
        return $this->SendSuccessResponse(null,$product_info);
    }
    public function GetByUrlCode(Request $request){
        if(!isset($request["currency_id"])){
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }
		
        $objProduct = Product::GetByUrlCode($request["url_code"]);
        if($objProduct!=null){
			$product_info = Product::FullProductInfoById($objProduct->id,$objCurrency->id);
			if(GenService::GenIntegrated()==1){

				$product = Product::GetProductById($objProduct->id);
				$gen_keys = json_decode($product->gen_keys);
				$price = GenService::GetPriceForItem($gen_keys->item_no,array("default_currency_code"=>$objCurrency->gen_code));
				$stock = GenService::GetStockForItem($gen_keys->item_no,$gen_keys->mfg_ser_lot_no);

                if($price!=null){
                    $product_info->online_price = $price[0];
                    $product_info->regular_price = $price[1];
                    $product_info->VISIBLE = 1;
                }else{
                    $product_info->online_price = "0.00";
                    $product_info->regular_price = null;
                    $product_info->VISIBLE = 0;
                }
				$product_info->stock = intval($stock);
  
			}
			return $this->SendSuccessResponse(null,$product_info);
		}else{
			return $this->SendErrorResponse(null,array());
		}

    }
    public function GetCatalogue(Request $request){
        
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
			$new_data =GenService::GetPriceItemList($data,array("default_currency_code"=>$objCurrency->gen_code),$request["order_by"]);
		}
        return $this->SendSuccessResponse(null,$new_data);
    }

    public function GetProductsBySpecificationFilters(Request $request){

        $category = Category::GetCategoryByName($request["categoryName"]);
        $objCurrency = Currency::GetByCode($request["currency_code"]);
        
        $data = Product::GetByFilters(
            $category[0]->id
			,isset($request["order_by"])?$request["order_by"]:1
            ,isset($request["search"])?$request["search"]:null
            ,isset($request["filters"])?$request["filters"]:null
            ,$objCurrency->id
            ,isset($request["min_price"])?$request["min_price"]:0
            ,isset($request["max_price"])?$request["max_price"]:999999
            ,isset($request["discounted"])?$request["discounted"]:null
            ,isset($request["page_num"])?$request["page_num"]:null
            ,isset($request["page_qty"])?$request["page_qty"]:Parameter::GetByCode('product_catalogue_quantity',null)
        );
        
        return $this->SendSuccessResponse(null,$data);
    }

    public function GetFullSpecificationByProductIdSpeId(Request $request){
        $product = Product::GetFullSpecificationByProductIdSpeId($request->product_id,$request->spe_id);
        return $this->SendSuccessResponse(null,$product);
    }
    public function GetFullSpecificationByProduct(Request $request){
        $product = Product::GetFullSpecificationByProduct($request->product_id,$request->spe_id);
        return $this->SendSuccessResponse(null,$product);
    }
    public function GetFilters(Request $request){
        $result = array();

        if(!isset($request["currency_id"])){
            $objCurrency = Currency::GetByCode($request["currency_code"]);
        }else{
            $objCurrency = Currency::GetById($request["currency_id"]);
        }
        $min_max_price = ProductPrice::GetMinMaxProductPriceByCategory($request["categories"],$objCurrency->id);
        $result["prices"] = $min_max_price;
        $result["currency"] = $objCurrency;
        return $this->SendSuccessResponse(null,$result);
    }
    public function GetUrlSpecifications(Request $request){
        $esp_lst = explode($request->separator,$request->specifications);
        $url_code=Product::GetProductBySepcifications($esp_lst,$request->group_code);
        return $this->SendSuccessResponse(null,$url_code);

    }
    public function GetComplement(Request $request){
        $objCurrency = Currency::GetByCode($request["currency_code"]);
        $data=Product::GetComplements($objCurrency->id, $request['category_id']);
        if(GenService::GenIntegrated()==1){
            $new_data = GenService::GetPriceItemList($data,array("default_currency_code"=>$objCurrency->gen_code),$request["order_by"]);
            $data = $new_data;
        }
        return $this->SendSuccessResponse(null, $data);
    }
}

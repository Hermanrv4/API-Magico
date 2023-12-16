<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Qualification;
use App\Http\Models\Database\Product;
use App\Http\Modules\Site\Services\GenService;

use Illuminate\Http\Request;

class QualificationController extends ApiController{
    public function GetById(Request $request){
		$data = Qualification::GetByIds($request["user_id"],$request["product_id"]);
        return $this->SendSuccessResponse(null,$data);
    }
	public function GetByUrlCode(Request $request){
		$data = Qualification::GetByIds($request["user_id"],$request["product_id"]);
        return $this->SendSuccessResponse(null,$data);
    }
    public function Add(Request $request){

		$deletedRows = Qualification::where('user_id', $request["user_id"])->where('product_id', $request["product_id"])->delete();
        $objNewQualification = new Qualification();
        $objNewQualification->user_id = $request["user_id"];
		$objNewQualification->rate = $request["rate"];
        $objNewQualification->product_id = $request["product_id"];
		$objNewQualification->description = $request["description"];
        $result=$objNewQualification->save();

        return $this->SendSuccessResponse(null,$result);
    }
	
	public function Get(Request $request){
			$data = Qualification::GetByProductId($request["product_id"]);
			return $this->SendSuccessResponse(null,$data);
    }
	
	public function GetByUrl(Request $request){
		
			$product = Product::GetProductByUrlCode($request["url_code"]);
		
			$data = Qualification::GetByProductId($product->id);
			return $this->SendSuccessResponse(null,$data);
    }
	
	
	
}

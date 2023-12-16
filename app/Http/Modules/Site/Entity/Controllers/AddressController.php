<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\ShippingPrice;
use App\Http\Models\Database\Ubication;
use App\Http\Models\Database\Address;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AddressController extends ApiController{
    public function GetByUser(Request $request){
		$Lstaddress = Address::GetByUserId($request["user_id"]);
		//LaravelLocalization::getCurrentLocale();
		for($i=0;$i<count($Lstaddress);$i++){
			$info_ubication = Ubication::GetById($Lstaddress[$i]->ubication_id);
			$Lstaddress[$i]->ubication = $info_ubication->name_localized;
		}
        
		return $this->SendSuccessResponse(null,$Lstaddress);
    }
    

    public function GetForUser(Request $request)
    {
        $address = Address::GetByUserId($request["user_id"]);
        return $this->SendSuccessResponse(null,$address);
    }
}
<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Shop;
use App\Http\Models\Database\ShippingPrice;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Ubication;
use Illuminate\Http\Request;
 
class ShopController extends ApiController{
    public function GetList(Request $request){
        $shops = Shop::GetAllVisible();
        return $this->SendSuccessResponse(null,$shops);
    }

    public function GetById(Request $request){
        return $this->SendSuccessResponse(null,Shop::GetById($request["id"]));
    }

}

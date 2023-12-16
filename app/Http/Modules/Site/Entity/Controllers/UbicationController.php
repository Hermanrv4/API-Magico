<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\ShippingPrice;
use App\Http\Models\Database\Ubication;
use Illuminate\Http\Request;

class UbicationController extends ApiController{
    public function Get(Request $request){
        if($request["root_ubication_id"]==-1){
            return $this->SendSuccessResponse(null,Ubication::GetRootParents());
        }else if(isset($request['code_ubication']) && $request['code_ubication']!=null && $request['code_ubication']!=""){
            return $this->SendSuccessResponse(null, Ubication::GetByCode($request['code_ubication']));
        }else{
            return $this->SendSuccessResponse(null,Ubication::GetChildsByRoot($request["root_ubication_id"]));
        }
    } 
    public function GetById(Request $request){
        return $this->SendSuccessResponse(null,Ubication::GetById($request["ubication_id"]));
    }
    public function GetFullChilds(Request $request){
        return $this->SendSuccessResponse(null, Ubication::GetFullChildsById($request['id_ubication']));
    }
}

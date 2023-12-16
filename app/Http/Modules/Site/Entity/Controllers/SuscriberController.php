<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Suscriber;
use App\Http\Models\Database\User;
use Illuminate\Http\Request;

class SuscriberController extends ApiController{
    public function Get(Request $request){
        //return $this->SendSuccessResponse(null,Cart::GetByUserId($request["user_id"]));
    }
    public function Add(Request $request){
        $suscriber = Suscriber::GetByEmail($request["email"]);
		
		if($suscriber==null){
			$newsuscriber = new Suscriber();
			
			if($request["email"]!=null && $request["email"]!="" && strpos($request["email"],"@")!=false){
				$newsuscriber->email = $request["email"];
			}else{
				return $this->SendErrorResponse(null,array());
			}
			
			if(isset($request["info_suscriber"])){
				if($request["info_suscriber"]==""){
					$newsuscriber->info_suscriber = $request["info_suscriber"];	
				}
			}
			$newsuscriber->save();
			return $this->SendSuccessResponse(null,$newsuscriber);
		}else{
			return $this->SendErrorResponse(null,null);
		}
        //return $this->SendSuccessResponse(null,Cart::GetByUserId($request["user_id"]));
    }
}

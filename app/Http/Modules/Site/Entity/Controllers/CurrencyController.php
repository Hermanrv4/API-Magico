<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Currency;
use Illuminate\Http\Request;

class CurrencyController extends ApiController{
    public function Get(Request $request){
        return $this->SendSuccessResponse(null,Currency::all());
    }
    public function GetById(Request $request){
        return $this->SendSuccessResponse(null,Currency::GetById($request["currency_id"]));
    }
}

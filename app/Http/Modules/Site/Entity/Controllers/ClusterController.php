<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Services\EntityService;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Product;
use Illuminate\Http\Request;

class ClusterController extends ApiController{
    public function Get(Request $request){
        return $this->SendSuccessResponse(null,Cluster::all());
    }
    public function GetByUrlCode(Request $request){
        $objCluster = Cluster::GetByUrlCode($request["url_code"]);
        return $this->SendSuccessResponse(null,$objCluster);
    }
}

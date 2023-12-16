<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use Illuminate\Http\Request;

class CategoryController extends ApiController{
    public function Get(Request $request){
        return $this->SendSuccessResponse(null,Category::all());
    }
    public function GetByUrlCode(Request $request){
        $obCategory = Category::GetByUrlCode($request["url_code"]);
        return $this->SendSuccessResponse(null,$obCategory);
    }
    public function GetById(Request $request){
        $obCategory = Category::GetById($request["category_id"]);
        return $this->SendSuccessResponse(null,$obCategory);
    }
    public function GetRootParents(Request $request){
        return $this->SendSuccessResponse(null,Category::GetRootParents());
    }
    public function GetRootParentsMenu(Request $request){
        return $this->sendSuccessResponse(null, Category::GetRootParentsMenu());
    }
    public function GetChildsByRoot(Request $request){
        return $this->SendSuccessResponse(null,Category::GetChildsByRoot($request["root_id"]));
    }
}

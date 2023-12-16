<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Modules\Admin\Services\ValidationLdProducts;

class LdSpecifications extends ApiController{
    public function register(Request $request){
        return $request;
    }
}
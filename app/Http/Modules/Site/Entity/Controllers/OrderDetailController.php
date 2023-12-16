<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Address;
use App\Http\Models\Database\Cart;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Currency;
use App\Http\Models\Database\Order;
use App\Http\Models\Database\OrderDetail;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Product;
use App\Http\Models\Database\ProductGroup;
use App\Http\Models\Database\ProductPrice;
use App\Http\Models\Database\Provider;
use App\Http\Models\Database\ShippingPrice;
use App\Http\Models\Database\Type;
use App\Http\Models\Database\User;
use App\Http\Modules\Site\Services\ValidationService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderDetailController extends ApiController{
    public function GetByOrder(Request $request){
        return $this->SendSuccessResponse(null,OrderDetail::GetByTableId(Order::class,$request["order_id"]));
    }
}

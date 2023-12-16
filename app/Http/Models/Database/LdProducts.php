<?php

namespace App\Http\Models\Database;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LdProducts extends BaseModel
{
    protected $table = 'ld_products';
    public $timestamps = false;
    protected $fillable = ['category_code','group_id','product_group','product','url_code','description','sku', 'is_catalogue', 'stock', 'regular_price', 'online_price', 'photos', 'especifications'];
    protected $string_json = ["product_group", "description", "product", "url_code"];
}
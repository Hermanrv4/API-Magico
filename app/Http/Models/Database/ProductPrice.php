<?php

namespace App\Http\Models\Database;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProductPrice extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'product_prices';
    protected $table_reference = 'product_price';
    protected $fillable = ['id','product_id','currency_id','regular_price','online_price','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_product_currency',function(Builder $builder){
            $builder
                ->join((new Product())->getTable(),
                    function($join){
                        $join->on('products.id','=','product_id');
                    })
                ->join((new Currency())->getTable(),
                    function($join){
                        $join->on('currencies.id','=','currency_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.product_id',
                    (new Product())->getTable().'.sku as product_sku',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_name"),
                    (new static())->getTable().'.currency_id',
                    (new Currency())->getTable().'.code as currency_code',
                    (new Currency())->getTable().'.symbol as currency_symbol',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Currency())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as currency_name"),
                    (new static())->getTable().'.regular_price',
                    (new static())->getTable().'.online_price',                    
                );
        });
    }

    public static function GetByProductIdAndCurrencyId($product_id,$currency_id){
        return ProductPrice::whereRaw('product_id = ? and currency_id = ?',[$product_id,$currency_id])->first();
    }
    public static function GetMinMaxProductPriceByCategory($categories,$currency_id){
        return DB::select("call usp_product_price_min_max (?,?) ",[$categories,$currency_id]);
    }

    public static function GetByProductId($product_id)
    {
        return ProductPrice::where((new static())->getTable().'.product_id','=',$product_id)->get();
    }
    public static function GetByCurrencyId($currency_id)
    {
        return ProductPrice::where((new static())->getTable().'.currency_id','=',$currency_id)->get();
    }
}

<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class OrderDetail extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'order_details';
    protected $table_reference = 'order_detail';
    protected $fillable = ['id','order_id','product_id','price','quantity','observations','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_product',function(Builder $builder){
            $builder
                ->join((new Product())->getTable(),
                    function($join){
                        $join->on('products.id','=','product_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.order_id',
                    (new static())->getTable().'.product_id',
                    (new Product())->getTable().'.sku as product_sku',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_name"),
                    (new static())->getTable().'.quantity',
                    (new static())->getTable().'.price',
                    (new static())->getTable().'.observations',
                );
        });
    }

    public static function GetByOrderId($order_id){
        return OrderDetail::where('order_details.order_id','=',$order_id)->get();
    }
}

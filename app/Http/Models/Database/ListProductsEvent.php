<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ListProductsEvent extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'list_products_events';
    protected $table_reference = 'list_products_event';
    protected $fillable = ['id','event_id','product_id','quantity','created_at','updated_at','quantity_acumulated'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>
    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_event_product',function(Builder $builder){
            $builder
                ->join((new Event())->getTable(),
                    function($join){
                        $join->on('events.id','=','event_id');
                    })
                ->join((new Product())->getTable(),
                    function($join){
                        $join->on('products.id','=','product_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.event_id',
                    (new Event())->getTable().'.name as event_name',
                    (new static())->getTable().'.product_id',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_name"),
                    (new static())->getTable().'.quantity',
                    (new static())->getTable().'.quantity_acumulated',
                );
        });
    }

    public static function GetByEventId($id){
        return ListProductsEvent::whereRaw('list_products_events.event_id = ?',[$id])->get();
    }
    public static function GetById($id){
        return ListProductsEvent::whereRaw('list_products_events.id = ?',[$id])->first();
    }
	public static function GetByIdProductId($event_id,$product_id){
        return ListProductsEvent::whereRaw('list_products_events.event_id = ? and list_products_events.product_id',[$event_id,$product_id])->first();
    }
	
	public static function UpdateByUserProduct($event_id,$product_id,$quantity){
		$product = ListProductsEvent::whereRaw('list_products_events.event_id = ? and list_products_events.product_id',[$event_id,$product_id])->first();
		$max = $product->quantity;
		$value = $product->quantity_acumulated;
		$is_success = 0;
		$value_temp = $value + intval($quantity);
		
		if($value_temp<$max){
			$product->quantity_acumulated = $value_temp;
			$product->save();
			$is_success = 1;
		}
		
		return $is_success;
	}
	
	public static function DeleteByEventId($id){
         return ListProductsEvent::where('list_products_events.event_id',$id)->delete();
    }
	public static function GetByProdEvent($event_id, $product_id){
        return ListProductsEvent::where((new static)->getTable().'.event_id','=',$event_id)->where((new static)->getTable().'.product_id','=',$product_id)->take(1)->get();
    }
}

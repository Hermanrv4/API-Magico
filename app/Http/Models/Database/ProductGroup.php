<?php

namespace App\Http\Models\Database;
use App\Http\Common\Services\DatabaseService;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductGroup extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'product_groups';
    protected $table_reference = 'product_group';
    protected $fillable = ['id','category_id','code','name','description','created_at','updated_at'];
    protected $string_json = ['name'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>
    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_category',function(Builder $builder){
            $builder
                ->join((new Category())->getTable(),
                    function($join){
                        $join->on('categories.id','=','category_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new Category())->getTable().'.id as category_id',
                    (new Category())->getTable().'.code as category_code',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as category_name"),
                    (new static())->getTable().'.code',
                    (new static())->getTable().'.description',
                    (new static())->getTable().'.name',

                );
                DatabaseService::AddJSONSelect((new static())->getTable(), $builder,(new static)->getStringJSON());
        });
    }

    public static function GetByCategoryId($category_id)
    {
        return ProductGroup::where((new Category())->getTableReference().'_id','=',$category_id)->get();
    }
    public function scopeGetGroupOrderBillingSale($query, $date_start, $date_end){
        return $query->join((new Product())->getTable(), (new Product())->getTable().'.product_group_id', "=", (new static())->getTable().'.id')
        ->join((new OrderDetail())->getTable(), (new OrderDetail())->getTable().'.product_id', "=", (new Product())->getTable().'.id' )
        ->join((new ElectronicBillingSale())->getTable(),(new ElectronicBillingSale())->getTable().'.order_id',"=",(new OrderDetail())->getTable().'.order_id')
        ->select(
            (new static())->getTable().'.id',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new static)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name"),
            DB::raw("count(".(new static())->getTable().".code) as cantidad_vendida")
        )->where(
            (new Product)->getTable().'.is_active', 1
        )->where(
            DB::raw("length(".(new ElectronicBillingSale)->getTable().".serie)"), "=", 4
        )->where(
            (new ElectronicBillingSale)->getTable().'.is_voided', "=", 0
        )->where(
            (new ElectronicBillingSale)->getTable().'.status', "=", 1
        )->WhereBetween(
            (new ElectronicBillingSale)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.id',
            (new static)->getTable().'.name'
        );
    }
    public function scopeGetGroupOrderBillingSaleOfDate($query, $date_start, $date_end){
        return $query->join( (new Product)->getTable(), (new Product)->getTable().'.product_group_id', "=", (new static)->getTable().'.id' )
        ->join( (new OrderDetail)->getTable(), (new OrderDetail)->getTable().'.product_id', "=", (new Product)->getTable().'.id' )
        ->join( (new ElectronicBillingSale)->getTable(), (new ElectronicBillingSale)->getTable().'.order_id', "=", (new OrderDetail)->getTable().'.order_id')
        ->select(
            (new static)->getTable().'.code as code_group',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new static)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_name"),
            (new Product)->getTable().'.sku as sku_product',
            (new ElectronicBillingSale)->getTable().'.serie',
            (new ElectronicBillingSale)->getTable().'.correlative'
        )->where(
            (new ElectronicBillingSale)->getTable().'.is_voided', "=", 0
        )->where(
            (new ElectronicBillingSale)->getTable().'.status', "=", 1
        )->where(
            DB::raw("length(".(new ElectronicBillingSale)->getTable().".serie)"), "=", 4
        )->WhereBetween(
            (new ElectronicBillingSale)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
}

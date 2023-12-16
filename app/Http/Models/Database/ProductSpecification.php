<?php

namespace App\Http\Models\Database;
use App\Http\Common\Services\DatabaseService;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;

class ProductSpecification extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'product_specifications';
    protected $table_reference = 'product_specification';
    protected $fillable = ['id','product_id','specification_id','value','created_at','updated_at'];
    protected $string_json = ['value'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_product_specification',function(Builder $builder){
            $builder
                ->join((new Product())->getTable(),
                    function($join){
                        $join->on('products.id','=','product_id');
                    })
                ->join((new Specification())->getTable(),
                    function($join){
                        $join->on('specifications.id','=','specification_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.value',
                    (new static())->getTable().'.product_id',
                    (new Product())->getTable().'.name as product_name',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_name_localized"),
                    (new static())->getTable().'.specification_id',
                    (new Specification())->getTable().'.name as specification_name',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Specification())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as specification_name_localized"),
                );
                DatabaseService::AddJSONSelect((new static())->getTable(), $builder,(new static)->getStringJSON());
        });
    } 
    
    public static function GetValuesByCategoriesAndSpecificationId($categories,$specification_id){ 
        return DB::select("select * from product_specifications where product_id in (select id from products where product_group_id in (select id from product_groups where category_id in (".$categories."))) and specification_id = ".$specification_id." order by value asc");

    }
    
    public static function GetBySpecificationId($specification_id){
        return ProductSpecification::where((new static())->getTable().'.specification_id','=',$specification_id)->get();
    }

    public static function GetByProductId($product_id){
        return ProductSpecification::where('product_specifications.product_id','=',$product_id)->get();
    }
}

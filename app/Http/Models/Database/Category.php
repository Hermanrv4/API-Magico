<?php

namespace App\Http\Models\Database;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Carbon\Carbon;
class Category extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'categories';
    protected $table_reference = 'category';
    protected $fillable = ['id','root_category_id','url_code','code','name','banner', 'show_menu','created_at','updated_at'];
    protected $string_json = ['url_code','name'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    public static function GetRootParents(){
        return Category::whereRaw('root_category_id is null',[])->get();
    }
    public static function GetRootParentsMenu(){
        return Category::whereRaw('root_category_id is null AND show_menu = 1',[])->get();
    }
    public static function GetChildsByRoot($root_id){
        return Category::whereRaw('root_category_id = ?',[$root_id])->get();
    }
    public static function GetById($id){
        return Category::whereRaw('id = ?',[$id])->first();
    }
    public function scopeGetCategorieOrderBillingDate($query, $date_start, $date_end){
        return $query->join( (new ProductGroup)->getTable(), (new ProductGroup)->getTable().'.category_id', "=", (new static)->getTable().'.id' )
        ->join( (new Product)->getTable(), (new Product)->getTable().'.product_group_id', "=", (new ProductGroup)->getTable().'.id' )
        ->join( (new OrderDetail)->getTable(), (new OrderDetail)->getTable().'.product_id', "=", (new Product)->getTable().'.id' )
        ->join( (new ElectronicBillingSale)->getTable(), (new ElectronicBillingSale)->getTable().'.order_id', "=", (new OrderDetail)->getTable().'.order_id')
        ->select( 
            (new static)->getTable().'.code as code_categorie',
            /* (new static)->getTable().'.name', */
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new static())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as categorie_name"),
            (new ProductGroup)->getTable().'.code as code_group',
            (new Product)->getTable().'.sku',
            /* (new Product)->getTable().'.name', */
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_name"),
            (new ElectronicBillingSale)->getTable().'.serie',
            (new ElectronicBillingSale)->getTable().'.correlative as numeracion'
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
    public static function GetByMenuCategory(){
        return Category::whereRaw( (new static)->getTable().'.show_menu = 1',[])->get();
    }

    public static function GetCategoryByName($categoryName){
        return Category::where('name', 'like', '%'. $categoryName .'%')->get();
    }
}

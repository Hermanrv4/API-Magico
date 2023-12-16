<?php

namespace App\Http\Models\Database;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Common\Services\DatabaseService;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class Product extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'products';
    protected $table_reference = 'product';
    protected $fillable = ['id','product_group_id','sku','url_code','name','description','photos','is_for_catalogue','is_active','stock','shipping_size','gen_keys','aditional_info','created_at','updated_at'];
    protected $string_json = ['url_code','name','description'];
    protected $hidden = [];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_product_group',function(Builder $builder){
            $builder
                ->join((new ProductGroup())->getTable(),
                    function($join){
                        $join->on('product_groups.id','=','product_group_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.product_group_id',
                    (new ProductGroup())->getTable().'.code as product_group_code',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new ProductGroup())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_group_name"),
                    (new static())->getTable().'.sku',
                    (new static())->getTable().'.name',
                    (new static())->getTable().'.url_code',
                    (new static())->getTable().'.description',
                    (new static())->getTable().'.is_for_catalogue',
                    (new static())->getTable().'.is_active',
                    (new static())->getTable().'.stock',
                    (new static())->getTable().'.shipping_size',
                    (new static())->getTable().'.gen_keys',
                    (new static())->getTable().'.aditional_info',
                    (new static())->getTable().'.photos',
                );
                DatabaseService::AddJSONSelect((new static())->getTable(), $builder,(new static)->getStringJSON());
        });
    }

    public static function GetLatest($currency_id,$limited){
		
		/*if($limited==1){
			return DB::select("call usp_product_get_latest (?,?,?,?) ",[$currency_id,$limited,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow()]);
		}else{*/
			return DB::select("call usp_product_get_latest (?,?,?) ",[$currency_id,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow()]);
		//}
		
    }
	public static function GetAll($currency_id,$limited){
		return DB::select("call usp_product_get_all (?,?,?) ",[$currency_id,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow()]);

    }
    public static function GetMoreSells($currency_id){
        return DB::select("call usp_product_get_more_sells (?,?,?) ",[$currency_id,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow()]);
    }
    public static function GetProms($currency_id){
        return DB::select("call usp_product_get_proms (?,?,?) ",[$currency_id,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow()]);
    }
    public static function GetComplements($currency_code, $category_id){
        return DB::select("call usp_product_get_complements(?,?,?,?) ",[$currency_code,$category_id,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow()]);
    }
    public static function GetMostSimiliars($currency_id,$id,$specifications, $category_id){
        return DB::select("call usp_product_get_most_similars (?,?,?,?,?,?) ",[$currency_id,$specifications,$id,LaravelLocalization::getCurrentLocale(),$category_id,DateHelper::GetNow()]);
    }
    public static function GetSimilars($product_group_id,$excluded_product_id,$filters,$currency_id){
        return DB::select("call usp_product_get_similars (?,?,?,?,?,?) ",[$product_group_id,$excluded_product_id,$filters,$currency_id,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow()]);
    }
    public static function GetByFilters($categories,$order_by,$search,$filters,$currency_id,$min_price,$max_price,$discounted,$page_num,$page_qty){
        return DB::select("call usp_product_get_by_filters (?,?,?,?,?,?,?,?,?,?,?,?) ",[$categories,$search,$filters,$currency_id,$min_price,$max_price,$discounted,$page_num,$page_qty,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow(),$order_by]);
    }
	public static function GetByFiltersEvents($categories,$order_by,$search,$filters,$currency_id,$min_price,$max_price,$discounted,$page_num,$page_qty,$ids){
        return DB::select("call USP_PRODUCT_GET_BY_EVENTS (?,?,?,?,?,?,?,?,?,?,?,?,?) ",[$categories,$search,$filters,$currency_id,$min_price,$max_price,$discounted,$page_num,$page_qty,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow(),$order_by,$ids]);
    }
    public static function FullProductInfoById($id,$currency_id){
        $data = DB::select("call usp_product_full_info (?,?,?,?) ",[$id,$currency_id,LaravelLocalization::getCurrentLocale(),DateHelper::GetNow()]);
        if(count($data)>0) return $data[0];
        return null;
    }

	public static function GetProductById($id){
		//return Product::whereRaw('id = ?', array($id))->first();
		return DB::select("select * from products where id=".$id)[0];
	} 
	public static function GetProductBySepcifications($esp,$group_code){

        $lst_sp = $esp;
        $query = "";
        $query_esp = "";
           for($i=0;$i<count($lst_sp);$i++){
            $query_esp.= "value like '%&".$lst_sp[$i]."&%' ";
            if($i!=(count($lst_sp)-1)){
                $query_esp.= "or ";
            }
        }
        $query_esp = str_replace('&','"',$query_esp);
        $query = "select JSON_UNQUOTE(JSON_EXTRACT(url_code, '$[0].es')) as 'URL_CODE' from products where id = (select product_id from product_specifications ps inner join specifications s on ps.specification_id = s.id
        where ps.product_id in 
       (select id from products where product_group_id = (select id from product_groups where code ='".$group_code."')) and
       (".$query_esp.") and
       s.is_preview = 1
       group by ps.product_id ";
       if(count($lst_sp)>1){
            $query .= 'having (count(ps.specification_id)>1)';
       }else{
            $query .= 'having (count(ps.specification_id)>0)';
       }
       $query.=")";
       return DB::select($query);

    }
	/*public static function GetProductByUrlCode($url){
		return Product::whereRaw('url_code = ?', array($url))->first();
	}
	*/
	public static function GetProductByUrlCode($url){
		return DB::select("select * from products where url_code like '%".$url."%'");
	}
    public static function GetFullSpecificationByProductIdSpeId($product_id,$sp_id){
        $query = "select JSON_UNQUOTE(JSON_EXTRACT(ps.value,'$[0].".LaravelLocalization::getCurrentLocale()."')) as value, 
        JSON_UNQUOTE(JSON_EXTRACT(p.url_code,'$[0].".LaravelLocalization::getCurrentLocale()."')) as url,
        p.stock as stock,
        p.id as id 
            from product_specifications ps inner join products p on p.id = ps.product_id 
        where ps.product_id in (select id from products where product_group_id = (select product_group_id from products where id = ".$product_id.")) 
        and ps.specification_id = ".$sp_id."";
        return DB::select($query);
    }
    public static function GetFullSpecificationByProduct($product_id){
        $query = "select JSON_UNQUOTE(JSON_EXTRACT(sp.name,'$[0].".LaravelLocalization::getCurrentLocale()."')) as name,JSON_UNQUOTE(JSON_EXTRACT(ps.value,'$[0].".LaravelLocalization::getCurrentLocale()."')) as value 
        from product_specifications ps inner join products p on p.id = ps.product_id 
        inner join specifications sp on sp.id = ps.specification_id  
        where ps.product_id = ".$product_id.""; 
        return DB::select($query);
    }
    public static function GetByProductGroup($product_group_id)
    {
        return Product::GetByTableId((new ProductGroup()),$product_group_id);
    }
    public function scopeGetByProductAndDate($query, $date_start, $date_end){
        return $query->join( (new OrderDetail)->getTable(), (new OrderDetail)->getTable().'.id', "=", (new static)->getTable().'.id')
        ->join( (new Order)->getTable(), (new Order)->getTable().'.id', '=', (new OrderDetail)->getTable().'.order_id')
        ->join( (new ElectronicBillingSale)->getTable(), (new ElectronicBillingSale)->getTable().'.order_id', "=", (new Order)->getTable().'.id')
        ->select(
            (new static)->getTable().'.id',
            (new static)->getTable().'.sku',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new static())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_prod"),
            (new Order)->getTable().'.id as id_order',
            DB::raw("count(".(new static)->getTable().".id) as count_product")
        )->where(
            (new ElectronicBillingSale)->getTable().'.is_voided', 0
        )->where(
            (new ElectronicBillingSale)->getTable().'.status',1
        )->where(
            (new static)->getTable().'.is_active',1
        )->where(
            DB::raw("length(".(new ElectronicBillingSale)->getTable().".serie)"), "<=", 4
        )->WhereBetween(
            (new ElectronicBillingSale)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.id',
            (new static)->getTable().'.sku',
            (new Order)->getTable().'.id',
            (new static)->getTable().'.name',
        );
    }
    public function scopeGetProductBillingSalesOfDate($query, $date_start, $date_end){
        return $query->join( (new OrderDetail)->getTable(), (new OrderDetail)->getTable().'.product_id', "=", (new static)->getTable().'.id' )
        ->join( (new Order)->getTable(), (new Order)->getTable().'.id', "=", (new OrderDetail)->getTable().'.order_id' )
        ->join( (new Type)->getTable(), (new Type)->getTable().'.id', "=", (new Order)->getTable().'.status_type_id')
        ->join( (new ElectronicBillingSale)->getTable(), (new ElectronicBillingSale)->getTable().'.order_id', "=", (new Order)->getTable().'.id' )
        ->select(
            (new OrderDetail)->getTable().'.id',
            (new static)->getTable().'.id',
            (new static)->getTable().'.sku',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new static())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_name"),
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as type_name"),
            (new ElectronicBillingSale)->getTable().'.serie',
            (new ElectronicBillingSale)->getTable().'.correlative'
            )->where(
                (new ElectronicBillingSale)->getTable().'.is_voided', "=", 0
            )->where(
                (new ElectronicBillingSale)->getTable().'.status', "=", 1
            )->where(
                DB::raw(" length(".(new ElectronicBillingSale)->getTable().".serie)"), "=", 4
            )->WhereBetween(
                (new ElectronicBillingSale)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
            );
    }
    public function scopeGetProductAll($query){
        return $query->join( (new ProductGroup)->getTable(), (new ProductGroup)->getTable().'.id', "=", (new static)->getTable().'product_group_id' )
        ->select(
            (new static())->getTable().'.id',
            (new static())->getTable().'.product_group_id',
            (new ProductGroup())->getTable().'.code as product_group_code',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new ProductGroup())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_group_name"),
            (new static())->getTable().'.sku',
            (new static())->getTable().'.name',
            (new static())->getTable().'.url_code',
            (new static())->getTable().'.description',
            (new static())->getTable().'.is_for_catalogue',
            (new static())->getTable().'.is_active',
            (new static())->getTable().'.stock',
            (new static())->getTable().'.shipping_size',
            (new static())->getTable().'.gen_keys',
            (new static())->getTable().'.photos',
        );
        DatabaseService::AddJSONSelect((new static())->getTable(), $query,(new static)->getStringJSON());
    }
}

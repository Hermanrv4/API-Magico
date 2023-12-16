<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ElectronicBillingSale extends BaseModel
{
    protected $table = 'electronic_billing_sales';
    protected $table_reference = 'electronic_billing_sale';
    protected $fillable = ['id','serie','correlative','order_id','status','is_voided','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_user',function(Builder $builder){
            $builder
                ->join((new Order())->getTable(),
                    function($join){
                        $join->on('orders.id','=','order_id');
                    })
                ->join((new User())->getTable(),
                function($join){
                    $join->on('users.id','=','user_id');
                })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.serie',
                    (new static())->getTable().'.correlative',
                    (new static())->getTable().'.order_id',
                    (new Order())->getTable().'.user_id',
                    (new User())->getTable().'.first_name as user_first_name',
                    (new User())->getTable().'.last_name as user_last_name',                    
                    (new Order())->getTable().'.sub_total as order_sub_total', 
                    (new Order())->getTable().'.total as order_total',
                    (new static())->getTable().'.status',
                    (new static())->getTable().'.is_voided',
                );
        });
    }

    public static function GetAllByOrderId($order_id)
    {
        return ElectronicBillingSale::where('order_id',$order_id)->get();
    }
    public static function GetByOrderId($order_id)
    {
        return ElectronicBillingSale::where('order_id',$order_id)->first();
    }

    public static function GetExistOrder($order_id){
        return ElectronicBillingSale::where('order_id',$order_id)->exists();
    }


    public function scopeGetSaleCategoriesDate($query, $date_start, $date_end)
    {
        return $query->join( (new Order)->getTable(), (new Order)->getTable().'.id', "=", (new static)->getTable().'.order_id' )
        ->join( (new OrderDetail)->getTable(), (new OrderDetail)->getTable().'.order_id', "=", (new Order)->getTable().'.id' )
        ->join( (new Product)->getTable(), (new Product)->getTable().'.id', "=", (new OrderDetail)->getTable().'.product_id' )
        ->join( (new ProductGroup)->getTable(), (new ProductGroup)->getTable().'.id', "=", (new Product)->getTable().'.product_group_id' )
        /* ->join( (new Category)->getTable(), (new Category)->getTable().'.id', "=", (new ProductGroup)->getTable().'.category_id' ) */
        ->select(
            (new ProductGroup())->getTable().'.category_id',
            /* (new Category())->getTable().'.name', */
            DB::raw("count(product_groups.category_id) as cantidades")
        )->where(
            (new static)->getTable().'.is_voided', 0
        )->where(
            (new static)->getTable().'.status', 1
        )->where(
            (new static)->getTable().'.serie', 'B001'
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy( (new ProductGroup)->getTable().'.category_id' );
    }
} 
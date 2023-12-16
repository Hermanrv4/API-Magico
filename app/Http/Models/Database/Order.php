<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Common\Services\DatabaseService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Order extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'orders';
    protected $table_reference = 'order';
    protected $fillable = ['id','user_id','wish_list_id','billing_address_id','aditional_info','shipping_address_id','currency_id','tax_percentaje','tax_amount','shipping_cost','sub_total','total', 'value_discount', 'id_discount','transaction_pay_code','payment_response','payment_status','receiver_info','observations','token','ordered_at','status_type_id','created_at','is_for_event','gen_response','event_id','type_store','id_shop','payment_type','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at']; 
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_user',function(Builder $builder){
            $builder
                ->join((new User())->getTable(),
                    function($join){
                        $join->on('users.id','=','user_id');
                    })
                ->join((new Type())->getTable(),
                    function($join){
                        $join->on('types.id','=','status_type_id');
                })
                ->join((new Currency())->getTable(),
                    function($join){
                        $join->on('currencies.id','=','currency_id');
                })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.user_id',
                    (new User())->getTable().'.first_name as user_first_name',
                    (new User())->getTable().'.last_name as user_last_name',
                    (new User())->getTable().'.email as user_email',
                    (new static())->getTable().'.wish_list_id',
                    (new static())->getTable().'.billing_address_id',
                    (new static())->getTable().'.shipping_address_id',
                    (new static())->getTable().'.currency_id',
                    (new static())->getTable().'.aditional_info',
                    (new Currency())->getTable().'.code as currency_code',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Currency())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as currency_name"),
                    (new static())->getTable().'.tax_percentaje',
                    (new static())->getTable().'.id_discount',
                    (new static())->getTable().'.value_discount',
                    (new static())->getTable().'.tax_amount',
                    (new static())->getTable().'.shipping_cost',
                    (new static())->getTable().'.sub_total',
                    (new static())->getTable().'.total',
                    (new static())->getTable().'.transaction_pay_code',
                    (new static())->getTable().'.payment_response',
                    (new static())->getTable().'.payment_status',
                    (new static())->getTable().'.receiver_info',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(orders.receiver_info ,'$.receiver_dni')) as dni"),
                    (new static())->getTable().'.observations',
                    (new static())->getTable().'.token',
                    (new static())->getTable().'.ordered_at',
                    (new static())->getTable().'.status_type_id',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as type_name"),
                    (new static())->getTable().'.is_for_event',
                    (new static())->getTable().'.event_id', 
                    (new static())->getTable().'.type_store', 
                    (new static())->getTable().'.id_shop', 
                    (new static())->getTable().'.gen_response',
                );
        });
    }

    public function scopeOrderbilled($query)
    {
        return $query->join((new User())->getTable(),
        function($join){
            $join->on('users.id','=','user_id');
        })
        ->join((new Type())->getTable(),
            function($join){
                $join->on('types.id','=','status_type_id');
        })
        ->join((new Currency())->getTable(),
            function($join){
                $join->on('currencies.id','=','currency_id');
        })
        ->leftJoin((new ElectronicBillingSale())->getTable(),
            function($join){
                $join->on('electronic_billing_sales.order_id','=','orders.id');
            }
        )
        ->select(
                (new static())->getTable().'.*',
                (new User())->getTable().'.first_name as user_first_name',
                (new User())->getTable().'.last_name as user_last_name',
                (new User())->getTable().'.email as user_email',
                (new Currency())->getTable().'.code as currency_code',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(orders.receiver_info ,'$.receiver_dni')) as dni"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Currency())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as currency_name"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as type_name"),
                (new ElectronicBillingSale())->getTable().'.serie as electronic_billing_sale_serie',
                (new ElectronicBillingSale())->getTable().'.correlative as electronic_billing_sale_correlative',
                (new ElectronicBillingSale())->getTable().'.status as electronic_billing_sale_status',
                (new ElectronicBillingSale())->getTable().'.is_voided as electronic_billing_sale_is_voided'
        );
    }

    public function scopeGetByProductAndDate($query,$product_id,$date)
    {
        return $query->join((new OrderDetail)->getTable(),(new OrderDetail)->getTable().'.order_id','=',(new static)->getTable().'.id')
            ->join((new User())->getTable(),
                function($join){
                    $join->on('users.id','=','user_id');
                })
            ->join((new Type())->getTable(),
                function($join){
                    $join->on('types.id','=','status_type_id');
            })
            ->join((new Currency())->getTable(),
                function($join){
                    $join->on('currencies.id','=','currency_id');
            })
            ->select(
                (new static())->getTable().'.id',
                (new static())->getTable().'.user_id',
                (new User())->getTable().'.first_name as user_first_name',
                (new User())->getTable().'.last_name as user_last_name',
                (new User())->getTable().'.email as user_email',
                (new static())->getTable().'.wish_list_id',
                (new static())->getTable().'.billing_address_id',
                (new static())->getTable().'.shipping_address_id',
                (new static())->getTable().'.currency_id',
                (new static())->getTable().'.aditional_info',
                (new Currency())->getTable().'.code as currency_code',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Currency())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as currency_name"),
                (new static())->getTable().'.tax_percentaje',
                (new static())->getTable().'.tax_amount',
                (new static())->getTable().'.shipping_cost',
                (new static())->getTable().'.sub_total',
                (new static())->getTable().'.total',
                (new static())->getTable().'.transaction_pay_code',
                (new static())->getTable().'.payment_response',
                (new static())->getTable().'.payment_status',
                (new static())->getTable().'.receiver_info',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(orders.receiver_info ,'$.receiver_dni')) as dni"),
                (new static())->getTable().'.observations',
                (new static())->getTable().'.token',
                (new static())->getTable().'.ordered_at',
                (new static())->getTable().'.status_type_id',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as type_name"),
                (new static())->getTable().'.is_for_event',
                (new static())->getTable().'.event_id', 
                (new static())->getTable().'.gen_response',
            )
            ->where(
                (new OrderDetail)->getTable().'.product_id',$product_id
            )
            ->whereDate(
                (new static)->getTable().'.ordered_at',$date
            );
    }

    public function scopeCustomerByDate($query,$date_start,$date_end)
    {
        return $query->join((new User())->getTable(),
            function($join){
                $join->on('users.id','=','user_id');
            })
            ->select(
                (new static())->getTable().'.user_id',
                DB::raw('count(*) as order_times')
            )
            ->whereBetween(
                (new static)->getTable().'.ordered_at',[$date_start,$date_end]
            )->groupBy('user_id');
    }
    public function scopeCustomerByDateName($query,$date_start,$date_end){
        return $query->join((new User())->getTable(),
            function($join){
                $join->on('users.id','=','user_id');
            })
            ->select(
                (new User())->getTable().'.first_name',
                DB::raw('count(orders.user_id) as order_times')
            )
            ->whereBetween(
                (new static)->getTable().'.ordered_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
            )->groupBy( (new User)->getTable().'.first_name');
    }
    public static function GetByToken($token){
        return Order::whereRaw('token like ?',[$token])->first();
    }
	
	public static function GetById($id){
        return Order::whereRaw('orders.id = ?',[$id])->first();
    }

    public static function ExistsTrxPayCode($trx){
        return count(Order::whereRaw('transaction_pay_code = ?', array($trx))->get())>0;
    }
    public static function UpdateDiscount($discount){

    }
    public static function GetByUserId($user_id){
        return Order::where('orders.user_id',$user_id)->get();
    }

    public static function GetAllOrderBilled()
    {
        return Order::withoutGlobalScopes()->orderbilled()
            ->where('orders.status_type_id',1)
            ->get();
    }

    public static function GetOrderBilledByOrderId($order_id)
    {
        return Order::withoutGlobalScopes()->orderbilled()->where('orders.id',$order_id)->first();
    }

    public static function GetByFieldsOrder($dni,$serie,$correlative,$total,$fec_emision)
    {
        return Order::withoutGlobalScopes()->orderbilled()
            ->where('orders.status_type_id',1)
            ->where('orders.total',floatval($total))
            ->whereDate('orders.ordered_at',$fec_emision)
            ->where('electronic_billing_sales.serie',$serie)
            ->where('electronic_billing_sales.correlative',$correlative)
            ->where('dni',$dni)
            ->first();
    }

    public static function GetByFilters($fec_emision=null,$currency=null)
    {
        if (is_null($currency)) {
            return Order::withoutGlobalScopes()->orderbilled()
            ->where('orders.status_type_id',1)
            ->whereDate('orders.ordered_at',$fec_emision)->get();
        }
        elseif(is_null($fec_emision)){
            return Order::withoutGlobalScopes()->orderbilled()
            ->where('orders.status_type_id',1)
            ->where('orders.currency_id',$currency)->get();
        }else{
            return Order::withoutGlobalScopes()->orderbilled()
            ->where('orders.currency_id',$currency)
            ->whereDate('orders.ordered_at',$fec_emision)
            ->where('orders.status_type_id',1)
            ->get();
        }        
        
    }
    public function scopeGetOrderStatusDate($query, $date_start, $date_end){
        return $query->join( (new Type)->getTable(), (new Type)->getTable().'.id', "=", (new static)->getTable().'.status_type_id')
        ->select(
            (new static)->getTable().'.status_type_id',
            (new Type)->getTable().'.code',
            (new Type)->getTable().'.name',
            DB::raw("count(".(new Type)->getTable().".code) as count_type"),
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as type_name"),
        )->WhereBetween(
            (new static)->getTable().'.ordered_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new Type)->getTable().'.code',
            (new Type)->getTable().'.name',
            (new static)->getTable().'.status_type_id',
        );
    }
    public function scopeGetOrderStatusOfDate($query, $date_start, $date_end, $id_status){
        return $query->join( (new Type)->getTable(),(new Type)->getTable().'.id', "=", (new static)->getTable().'.status_type_id' )
        ->select(
            (new static)->getTable().'.status_type_id',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as type_name"),
            DB::raw("SUBSTRING(".(new static)->getTable().".ordered_at, 1, 10) as orderet_at"),
            DB::raw("count(*) as count_status"),
        )->where(
            (new Type)->getTable().'.id', "=", $id_status
        )->WhereBetween(
            (new static)->getTable().'.ordered_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()] 
        )->groupBy(
            DB::raw("SUBSTRING(".(new static)->getTable().".ordered_at, 1, 10)"),
            (new Type)->getTable().'.name',
            (new static)->getTable().'.status_type_id',
        );
    }
    public function scopeGetOrderForStatusOfDate($query, $date_start, $date_end, $status_type){
        return $query->join( (new Type)->getTable(), (new Type)->getTable().'.id', "=", (new static)->getTable().'.status_type_id' )
        ->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.user_id' )
        ->join( (new Currency)->getTable(), (new Currency)->getTable().'.id', "=", (new static)->getTable().'.currency_id')
        ->select(
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name',
            (new static)->getTable().'.total',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Currency)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as Currency"),
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as Status"),
            (new static)->getTable().'.ordered_at'
        )->where(
            (new Type)->getTable().'.id', "=", $status_type
        )->WhereBetween(
            (new static)->getTable().'.ordered_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    public function scopeGetOrderOfDate($query, $date_start, $date_end){
        return $query->join( (new Type)->getTable(), (new Type)->getTable().'.id', "=", (new static)->getTable().'.status_type_id' )
        ->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.user_id' )
        ->join( (new Currency)->getTable(), (new Currency)->getTable().'.id', "=", (new static)->getTable().'.currency_id')
        ->select(
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name',
            (new static)->getTable().'.total',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Currency)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as Currency"),
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as Status"),
            (new static)->getTable().'.ordered_at'
        )->WhereBetween(
            (new static)->getTable().'.ordered_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
}

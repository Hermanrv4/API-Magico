<?php

namespace App\Http\Models\Database;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ShippingPrice extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'shipping_prices';
    protected $table_reference = 'shipping_price';
    protected $fillable = ['id','ubication_id','currency_id','price','min_days','is_static','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = [];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_ubications_currrency',function(Builder $builder){
            $builder
                ->join((new Ubication())->getTable(),
                    function($join){
                        $join->on('ubications.id','=','ubication_id');
                    })
                ->join((new Currency())->getTable(),
                    function($join){
                        $join->on('currencies.id','=','currency_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new Ubication())->getTable().'.id as ubication_id',
                    (new Ubication())->getTable().'.code as ubication_code',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Ubication())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as ubication_name"),
                    (new Currency())->getTable().'.id as currency_id',
                    (new Currency())->getTable().'.code as currency_code',
                    (new Currency())->getTable().'.symbol as currency_symbol',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Currency())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as currency_name"),
                    (new static())->getTable().'.price',
                    (new static())->getTable().'.min_days',
                    (new static())->getTable().'.is_static',
                );
        });
    }

    public static function GetByCurrencyId($currency_id)
    {
        return ShippingPrice::where((new static())->getTable().'.currency_id','=',$currency_id)->get();
    }
    public static function GetByUbicationId($ubication_id)
    {
        return ShippingPrice::where((new static())->getTable().'.ubication_id','=',$ubication_id)->get();
    }

    public static function GetByUbicationIdAndCurencyId($ubication_id,$currency_id){

        return ShippingPrice::whereRaw('ubication_id = ? and currency_id = ?',[$ubication_id,$currency_id])->first();
    }
}

<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Carbon\Carbon;

class Shop extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'shops';
    protected $table_reference = 'shop';
    protected $fillable = ['id','code','name','description','photo','location_longitude','location_latitude','address','ubication_id','is_visible','created_at','updated_at'];
    protected $string_json = ['name','description'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>
    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_data_shops',function(Builder $builder){
            $builder
            ->join((new Ubication())->getTable(),
            function($join){
                $join->on('ubications.id','=','ubication_id');
            })
                /* ->join((new WishList())->getTable(),
                    function($join){
                        $join->on('wish_lists.id','=','wish_list_id');
                    }) */
                ->select(
                    (new static)->getTable().'.id',
                    (new static)->getTable().'.code',
                    (new static)->getTable().'.name',
                    (new static)->getTable().'.description',
                    (new static)->getTable().'.photo',
                    (new static)->getTable().'.location_longitude',
                    (new static)->getTable().'.location_latitude',
                    (new static)->getTable().'.address',
                    (new static)->getTable().'.ubication_id',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Ubication())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as ubication_name"),
                    (new static)->getTable().'.is_visible',
                );
                DatabaseService::AddJSONSelect((new static())->getTable(), $builder,(new static)->getStringJSON());
        });
    }
    public static function GetAllVisible(){
        $all = Shop::where( (new static)->getTable().'.is_visible', "=" , 1)->get();
        return $all;
    }

    public static function GetById($id){
        return Shop::whereRaw((new static)->getTable().'.id = ?',[$id])->first();
    }
}

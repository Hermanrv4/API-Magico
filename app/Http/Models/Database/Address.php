<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Address extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'addresses';
    protected $table_reference = 'address';
    protected $fillable = ['id','user_id','wish_list_id','ubication_id','address','phone','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_user_ubication',function(Builder $builder){
            $builder
                ->join((new User())->getTable(),
                    function($join){
                        $join->on('users.id','=','user_id');
                    })
                ->join((new Ubication())->getTable(),
                    function($join){
                        $join->on('ubications.id','=','ubication_id');
                    })
                /* ->join((new WishList())->getTable(),
                    function($join){
                        $join->on('wish_lists.id','=','wish_list_id');
                    }) */
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.user_id',
                    (new User())->getTable().'.first_name as user_first_name',
                    (new User())->getTable().'.last_name as user_last_name',
                    (new static())->getTable().'.wish_list_id',
                    //(new WishList())->getTable().'.name as wish_list_name',
                    (new static())->getTable().'.ubication_id',
                    (new Ubication())->getTable().'.code as ubication_code',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Ubication())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as ubication_name"),
                    (new static())->getTable().'.address',
                    (new static())->getTable().'.phone',
                );
        });
    }

	
	public static function GetByUserId($user_id){
        return Address::whereRaw('user_id = ?',[$user_id])->get();
    }
    public static function GetByAdd($id){
        return DB::select("select * from specifications where id in (".$id.")");
    }
	
    public static function UpByAdd($column,$value,$byId){
        return DB::select("update addresses set ".$column."='".$value."' where id = '".$byId."'");
    }
    public static function DelByAdd($byId){
        return DB::select("delete from addresses where id = ".$byId);
    }

	public static function GetByUserIdUbicationId($user_id,$ubication_id){
        return Address::whereRaw('user_id = ? and ubication_id = ?',[$user_id,$ubication_id])->first();
    }
	
	public static function GetByAllData($user_id,$ubication_id,$address){
        return Address::whereRaw('user_id = ? and ubication_id = ? and address = ?',[$user_id,$ubication_id,$address])->first();
    }
}

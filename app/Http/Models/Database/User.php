<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseAuthModel;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class User extends BaseAuthModel implements JWTSubject
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'users';
    protected $table_reference = 'user';
    protected $fillable = ['id','dni','first_name','last_name','phone','email','password','facebook_id','is_admin','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['password', 'created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    // <editor-fold desc="JWT AUTH" defaultstate="collapsed">
    public function getJWTIdentifier(){
        return $this->getKey();
    }
    public function getJWTCustomClaims(){
        return [];
    }
    // </editor-fold>

    public static function GetByEmailAndPassword($email,$password){
        return User::whereRaw('email like ? and password like ?',[$email,$password])->first();
    }
    public static function GetByFacebookId($facebook_id){
        return User::whereRaw('facebook_id like ?',[$facebook_id])->first();
    }
	public static function GetById($id){
        return User::whereRaw('id like ?',[$id])->first();
    }
	public static function GetByEmail($email){
        return User::whereRaw('email = ?',[$email])->first();
    }
    ###########################################
    public static function GetByIsAdmin($is_admin){
        return User::where('is_admin',$is_admin)->get();
    }
    public static function scopeGetUserOfDateRegister($query,$date_start, $date_end){
        return $query->select( 
            DB::raw("SUBSTRING(".(new static)->getTable().".created_at, 1, 10) as date_reg"),
            DB::raw("count(SUBSTRING(".(new static)->getTable().".created_at, 1, 10)) as contador")
            )->WhereBetween(
                (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
            )->groupBy(
                DB::raw("SUBSTRING(".(new static)->getTable().".created_at, 1, 10)")
            );
    }
    public function scopeGetOrderUserOfDate($query, $date_start, $date_end){
        return $query->join( (new Order)->getTable(), (new Order)->getTable().'.user_id', "=", (new static)->getTable().'.id' )
        ->join( (new Type)->getTable(), (new Type)->getTable().'.id', "=", (new Order)->getTable().'.status_type_id' )
        ->select(
            (new static)->getTable().'.dni',
            (new static)->getTable().'.first_name as name',
            (new static)->getTable().'.last_name',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as type_name"),
            (new Order)->getTable().'.total',
            (new Order)->getTable().'.ordered_at as Ordenado en'
        )->WhereBetween(
            (new Order)->getTable().'.ordered_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    public function scopeGetBillingUserOfDate($query, $date_start, $date_end){
        return $query->join( (new Order)->getTable(), (new Order)->getTable().'.user_id', "=", (new static)->getTable().'.id' )
        ->join( (new ElectronicBillingSale)->getTable(), (new ElectronicBillingSale)->getTable().'.order_id', "=",(new Order)->getTable().'.id' )
        ->select(
            (new static)->getTable().'.first_name',
            DB::raw("count(".(new static)->getTable().".id) as count_data"),
        )->where(
            (new ElectronicBillingSale)->getTable().'.is_voided', "=", 0
        )->where(
            (new ElectronicBillingSale)->getTable().'.status', "=", 1
        )->where(
            DB::raw("length(".(new ElectronicBillingSale)->getTable().".serie)"), "=", 4
        )->WhereBetween(
            (new ElectronicBillingSale)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.id',
            (new static)->getTable().'.first_name',
        );
    }
    public function scopeGetUserBillingUserOfDate($query, $date_start, $date_end){
        return $query->join( (new Order)->getTable(), (new Order)->getTable().'.user_id', "=", (new static)->getTable().'.id' )
        ->join( (new ElectronicBillingSale)->getTable() ,(new ElectronicBillingSale)->getTable().'.order_id', "=", (new Order)->getTable().'.id')
        ->select(
            (new static)->getTable().'.id',
            (new static)->getTable().'.first_name',
            (new static)->getTable().'.dni',
            (new ElectronicBillingSale)->getTable().'.serie',
            (new ElectronicBillingSale)->getTable().'.correlative',
            (new ElectronicBillingSale)->getTable().'.created_at',
            (new Order)->getTable().'.total'
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
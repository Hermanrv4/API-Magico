<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Suscriber extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'suscribers';
    protected $table_reference = 'suscriber';
    protected $fillable = ['id','email','info_suscriber','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    public static function GetByUserId($user_id){
        return Suscriber::whereRaw('user_id = ?',[$user_id])->get();
    }
	public static function GetByEmail($email){
        return Suscriber::whereRaw('email = ?',[$email])->first();
    }
    public static function GetByUserIdAndProductId($user_id,$product_id){
        return Suscriber::whereRaw('user_id = ? and product_id = ?',[$user_id,$product_id])->first();
    }
    public static function DeleteByUserIdAndProductId($user_id,$product_id){
        return Suscriber::whereRaw('user_id = ? and product_id = ?',[$user_id,$product_id])->delete();
    }
    public function scopeSuscriberDate($query, $date_start, $date_end){
        return $query->select(
            /* (new static)->getTable().'.id', */
            /* (new static)->getTable().'.email', */
            DB::raw("SUBSTRING(".(new static)->getTable().".created_at, 1, 10) as fec_date"),
            DB::raw("count(*) as suscritos_today"),
        )->WhereBetween(
            (new static)->getTable().'.created_at',[Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            DB::raw("SUBSTRING(".(new static)->getTable().".created_at, 1, 10)"),
            /* (new static)->getTable().'.email', */
        );
    }
}

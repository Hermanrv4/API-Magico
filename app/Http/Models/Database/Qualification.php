<?php

namespace App\Http\Models\Database;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Qualification extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'qualifications';
    protected $table_reference = 'qualification';
    protected $fillable = ['id','user_id','product_id','rate','description','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    public static function GetByIds($user_id,$product_id){
        return Qualification::whereRaw('user_id = ? and product_id = ?',[$user_id,$product_id])->first();
    }
	public static function GetByProductId($id){
        return Qualification::whereRaw('product_id = ?',[$id])->get();
    }

}

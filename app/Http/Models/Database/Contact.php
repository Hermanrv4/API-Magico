<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;

class Contact extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'contacts';
    protected $table_reference = 'contact';
    protected $fillable = ['id','email','first_name','last_name','phone','message','is_company','name_company','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    public static function GetByUserId($user_id){
        return Contact::whereRaw('user_id = ?',[$user_id])->get();
    }
	public static function GetByEmail($email){
        return Contact::whereRaw('email = ?',[$email])->first();
    }
    public static function GetByUserIdAndProductId($user_id,$product_id){
        return Contact::whereRaw('user_id = ? and product_id = ?',[$user_id,$product_id])->first();
    }
    public static function DeleteByUserIdAndProductId($user_id,$product_id){
        return Contact::whereRaw('user_id = ? and product_id = ?',[$user_id,$product_id])->delete();
    }
}

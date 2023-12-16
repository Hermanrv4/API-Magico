<?php
namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
class ClaimBook extends BaseModel
{
    protected $table = 'claim_books';
    protected $table_reference = 'claim_book';
    protected $fillable = ['id','type','correlative','customer_name','customer_dni','customer_address','customer_email','customer_phone','flg_younger','parent_name','parent_dni','parent_address','parent_email','parent_phone','status','detail_product','detail','detail_answer','date_register','date_answer','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];

    public static function GetByDNI($dni)
    {
        return ClaimBook::where('customer_dni',$dni)->get();
    }
}
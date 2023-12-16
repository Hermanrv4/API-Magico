<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;

class WishListProduct extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'wish_list_products';
    protected $table_reference = 'wish_list_product';
    protected $fillable = ['id','wish_list_id','product_id','quantity','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

}

<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;

class WishList extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'wish_lists';
    protected $table_reference = 'wish_list';
    protected $fillable = ['id','organizer_user_id','name','description','wished_at','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

}

<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;

class TypeGroup extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'type_groups';
    protected $table_reference = 'type_group';
    protected $fillable = ['id','code','name','created_at','updated_at'];
    protected $string_json = ['name'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>
}

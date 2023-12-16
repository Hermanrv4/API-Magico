<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;

class Currency extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'currencies';
    protected $table_reference = 'currency';
    protected $fillable = ['id','code','gen_code','symbol','name','created_at','updated_at'];
    protected $string_json = ['name'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>
} 

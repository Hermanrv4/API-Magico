<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;

class CategorySpecification extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'category_specifications';
    protected $table_reference = 'category_specification';
    protected $fillable = ['id','category_id','specification_id','is_filter','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    public static function GetByCategoryId($category_id){
        return CategorySpecification::where((new static())->getTable().'.'.(new Category())->getTableReference().'_id','=',$category_id)->get();
    }

}

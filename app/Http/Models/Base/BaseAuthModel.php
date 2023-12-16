<?php

namespace App\Http\Models\Base;
use App\Http\Common\Services\DatabaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BaseAuthModel extends Authenticatable {
    protected $string_json;
    protected $table_reference;
    // <editor-fold desc="Boot Options" defaultstate="collapsed">
    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_string_values', function (Builder $builder) {
            $builder->select();
            DatabaseService::AddJSONSelect((new static())->getTable(),$builder,(new static)->getStringJSON());
        });
        static::addGlobalScope('get_status_active', function (Builder $builder) {
            if(in_array("is_active", (new static)->getFillable())) {
                $builder->where('is_active', '=', true);
            }
        });
    }
    // </editor-fold>
    // <editor-fold desc="Getters" defaultstate="collapsed">
    public static function getStringJSON(){
        return (new static)->string_json;
    }
    public static function getTableReference(){
        return (new static)->table_reference;
    }
    // </editor-fold>
    // <editor-fold desc="Generic Static Functions" defaultstate="collapsed">
    public static function GetByTableId($model,$id,$pre_specification=NULL){
        return Self::where(($pre_specification==NULL?"":$pre_specification."_").$model::getTableReference().'_id',$id)->get();
    }
    public static function GetById($id){
        return Self::where('id',$id)->first();
    }
    public static function GetByRootId($root_id){
        return Self::where('root_'.(new static)->getTableReference().'_id',$root_id)->get();
    }
    public static function GetByCode($code){
        return Self::where('code',$code)->first();
    }
    public static function GetByUrlCode($url_code){
        return DatabaseService::AddJSONWhere((new static)->getTable(),Self::select(),array('url_code'),array($url_code))->first();
    }
    // </editor-fold>
}

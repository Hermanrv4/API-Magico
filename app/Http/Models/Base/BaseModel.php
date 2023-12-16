<?php

namespace App\Http\Models\Base;
use App\Http\Common\Services\DatabaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BaseModel extends Model{
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
                $builder->where('is_active', '=', true)->orWhere('is_active', "=", false);
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
    public static function DeleteByTableId($model,$id,$pre_specification=NULL){
        return Self::withoutGlobalScopes()->where(($pre_specification==NULL?"":$pre_specification."_").$model::getTableReference().'_id',$id)->delete();
    }
    public static function DeleteById($id){
        return Self::withoutGlobalScopes()->where('id',$id)->delete();
    }
    public static function DesactivateByTableId($model,$id,$pre_specification=NULL){
        if(in_array("is_active", (new static)->getFillable())) {
            $obj = Self::withoutGlobalScopes()->where(($pre_specification==NULL?"":$pre_specification."_").$model::getTableReference().'_id',$id)->first();
            if($obj!=null) {
                $obj->is_active = 0;
                $obj->save();
            }
            return true;
        }else{
            return false;
        }
    }
    public static function Desactivate($id){
        if(in_array("is_active", (new static)->getFillable())) {
            $obj = Self::withoutGlobalScopes()->where('id',$id)->first();
            if($obj!=null) {
                $obj->is_active = 0;
                $obj->save();
            }
            return true;
        }else{
            return false;
        }
    }
    //cambiar estado 
    public static function ChangeActive($id, $value){
        if(in_array('is_active', (new static)->getFillable())){
            $obj = Self::withoutGlobalScopes()->where('id', $id)->first();
            if($obj!=null){
                $obj->is_active = $value;
                $obj->save();
            }
            return true;
        }else{
            return false;
        }
    }
    //cambiar estado 
    public static function GetById($id){
        return Self::where((new static)->getTable().'.id',$id)->first();
    }
    public static function GetByRootId($root_id){
        return Self::where('root_'.(new static)->getTableReference().'_id',$root_id)->get();
    }
    public static function GetByCode($code){
        return Self::where((new static)->table.'.code',$code)->first();
    }
    public static function GetByUrlCode($url_code){
        return DatabaseService::AddJSONWhere((new static)->getTable(),Self::select(),array('url_code'),array($url_code))->first();
    }
    // </editor-fold>
}

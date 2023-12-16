<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;

class Ubication extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'ubications';
    protected $table_reference = 'ubication';
    protected $fillable = ['id','root_ubication_id','is_pick_city','code','name','is_active','created_at','updated_at'];
    protected $string_json = ['name'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    public static function GetRootParents(){
        return Ubication::whereRaw('root_ubication_id is null',[])->get();
    }
    public static function GetChildsByRoot($root_id){
        return Ubication::whereRaw('root_ubication_id = ? and is_active = ?',[$root_id,1])->get();
    }
	public static function GetById($id){
        return Ubication::whereRaw('id = ?',[$id])->first();
    }
    public static function GetFullChildsById($ubication_id)
    {
        $childs_id = array();
        $childs = json_decode(Ubication::GetChildsByRoot($ubication_id),true);
        for ($i=0; $i < count($childs); $i++) {
            $childs_id[] = $childs[$i]['id'];
            $childs2 = Ubication::GetFullChildsById($childs[$i]['id']);
            if (!is_null($childs2) || count($childs2)!=0) {
                $childs_id = array_merge($childs_id,$childs2);
            }
        }
        return $childs_id;
    }
}

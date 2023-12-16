<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Models\Database\Parameter;
use Exception;
use Illuminate\Support\Facades\DB;
class Specification extends BaseModel

{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'specifications';
    protected $table_reference = 'specification';
    protected $fillable = ['id','code','name','is_preview','is_color','is_html','is_image','is_global_filter','created_at','updated_at'];
    protected $string_json = ['name'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>
    public function scopeSpecificationCodes($query)
    {
        return $query
        ->select(
                (new static())->getTable().'.id',
                (new static())->getTable().'.code',
        );
    }

    public static function GetByCodeSpecification($specification_code)
    {
        if($specification_code==Parameter::GetByCode('default_id')){
            return Specification::withoutGlobalScopes()->SpecificationCodes()->get();
        }else{
            return Specification::where('specifications.code',$specification_code)->get();
        }
    }

    public static function GetByIdSpecifications($specification_ids)
    {
        return DB::select("select * from specifications where id in (".$specification_ids.")");
        //return Cart::whereRaw('id in ?',[$specification_ids])->get();
       // return Specification::where('specifications.id','like',$specification_ids)->get();

    }
    public static function GetFilterSpecification($code="1"){
        //obtener parametros
        if(Parameter::where( (new Parameter)->getTable().'.code', "=", 'list_filter_order')->exists()){
            $objParameter=json_decode(Parameter::GetByCode('list_filter_order'), true);
            //validar si el estatus de editar filtro esta habilitado
            try{
                if($objParameter["status_filter_edit"]==1){
                    if(count($objParameter)>0){
                        $list = Specification::all();
                        $array=[];
                        for($item=0; $item<count($objParameter['list']); $item++){
                            if($objParameter['list'][$item]['categorie_id']==$code){
                                $list=$objParameter['list'][$item];
                                for($a=0; $a<count($list['order']); $a++){
                                    if($list['order'][$a]['status']==1 && Specification::where((new static)->getTable().'.id', "=", $list['order'][$a]['id_specification'])->exists()){
                                        $array[]=Specification::GetById($list['order'][$a]['id_specification']);
                                    }
                                }
                            }
                        }
                        return $array;
                    }else{
                        return Specification::all();
                    }
                }else{
                    return Specification::all();
                }
            }catch(Exception $e){
                return Specification::all();
            }
        }else{
            return Specification::all();
        }
    }

    public static function GetAllSpecifications(){
        return Specification::all();
    }
}

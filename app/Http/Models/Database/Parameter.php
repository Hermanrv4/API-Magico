<?php

namespace App\Http\Models\Database;
use App\Http\Common\Services\DatabaseService;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PhpParser\Node\Param;

class Parameter extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'parameters';
    protected $table_reference = 'parameter';
    protected $fillable = ['id','parameter_type_id','code','value','is_json','is_localized','is_modify', 'description_code','created_at','updated_at'];
    protected $string_json = ['value'];
    protected $hidden = [];
    // </editor-fold>

    // <editor-fold desc="Boot Options" defaultstate="collapsed">
    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_type', function (Builder $builder) {
            $builder
                ->join((new Type())->getTable(),
                function($join){
                    $join->on((new Type())->getTable().'.id', '=', 'parameter_type_id');
                })
                ->select(
            (new Type())->getTable().'.code AS parameter_type_code'
                    ,(new static())->getTable().'.id'
                    ,(new static())->getTable().'.code'
                    ,(new static())->getTable().'.value'
                    ,(new static())->getTable().'.is_localized'
                    ,(new static())->getTable().'.is_json'
                    ,(new static())->getTable().'.is_modify'
                    ,(new static())->getTable().'.description_code'
                    ,(new static())->getTable().'.updated_at'
                );
            DatabaseService::AddJSONSelect((new static())->getTable(),$builder,(new static)->getStringJSON());
        });
    }
    // </editor-fold>
    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    public static function GetLastUpdatedDate(){
        return Parameter::orderBy('updated_at', 'desc')->first()->updated_at;
    }
    public static function QuickSave($parameter_type_code,$code,$value,$is_json=null,$is_localized=null,$is_modify=null){
        $objParam = new Parameter();
        $objParam->parameter_type_id = Type::GetByCode($parameter_type_code)->id;
        $objParam->code = $code;
        $objParam->value = $value;
        $objParam->is_json = $is_json;
        $objParam->is_localized = $is_localized;
        $objParam->is_modify = $is_modify;
        $objParam->save();
        $objParam->code = $code;//Para generar el update_at
        $objParam->save();
        return true;
    }
    // </editor-fold>
    public static function GetByCode($code,$default = ''){
        $objPrm = Parameter::whereRaw("code like ?",$code)->withoutGlobalScope('get_type')->first();
        if($objPrm==null){
            return $default;
        }else{
            return $objPrm->value;
        }
    }
    //
    public static function GetCodes()
    {
        return Parameter::withoutGlobalScopes()->where('is_modify',1)->get(['id','code']);
    }
    public static function GetByCodeValue($code){
        return $objParam=Parameter::whereRaw("code like ? ", $code)->withoutGlobalScope('get_type')->first();
    }
    public static function GetLanding(){
        $objslide=Parameter::GetByCode('slide_landing');
        $objslide=json_decode($objslide, true);
        $lang=LaravelLocalization::getCurrentLocale();
        return $objslide[0][$lang];
    }
    public static function GetJsonValues($code){
        $objValues=Parameter::select('value')->whereRaw((new Parameter)->getTable().'.code like ?', $code)->get();
        return json_decode($objValues, true);
    }

    public static function UpdateValueByCode($code, $value){
        $objParameter = Parameter::GetByCodeValue($code);
        $objParameter->value = (is_null($value) ? null : $value);
        $objParameter->save();
        return $objParameter;     
    }
}

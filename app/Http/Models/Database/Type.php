<?php

namespace App\Http\Models\Database;
use App\Http\Common\Services\DatabaseService;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\DB;

class Type extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'types';
    protected $table_reference = 'type';
    protected $fillable = ['id','type_group_id','code','name','photo','extra_info','created_at','updated_at'];
    protected $string_json = ['name'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    protected static function boot(){
        parent::boot();
        static::addGlobalScope('get_type_group',function(Builder $builder){
            $builder
                ->join((new TypeGroup())->getTable(),
                    function($join){
                        $join->on('type_groups.id', '=', 'type_group_id');
                    })
                ->select(
                    (new static())->getTable().'.id',
                    (new static())->getTable().'.type_group_id',
                    (new TypeGroup())->getTable().'.code as type_group_code',
                    (new TypeGroup())->getTable().'.name as type_group_name',
                    DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new TypeGroup())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as type_group_name_localized"),
                    (new static())->getTable().'.code as code',
                    (new static())->getTable().'.name as name',                    
                );
                DatabaseService::AddJSONSelect((new static())->getTable(), $builder,(new static)->getStringJSON());
        });
    }

    public static function GetByTypeGroupId($type_group_id)
    {
        return Type::where('type_group_id','=',$type_group_id)->get();
    }

    public static function GetByCode($code)
    {
        return Type::where((new static)->getTable().'.code','=',$code)->first();
    }
}

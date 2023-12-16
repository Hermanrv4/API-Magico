<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Common\Services\DatabaseService;
use Illuminate\Support\Facades\DB;

class ElectronicBilling extends BaseModel
{
    protected $table = 'electronic_billing';
    protected $table_reference = 'electronic_billing';
    protected $fillable = ['id','serie','correlative','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];


    public static function GetCorrelative($serie)
    {
        $electronic_billing =  ElectronicBilling::firstOrCreate(['serie'=>$serie],['correlative'=>'00000000']);
        return $electronic_billing;
    }

    public static function QuickUpdate($serie,$correlative)
    {
        $electronic_billing = ElectronicBilling::where('serie',$serie)->first();
        $electronic_billing->correlative = $correlative;        
        $electronic_billing->save();
        return true;
    }
} 
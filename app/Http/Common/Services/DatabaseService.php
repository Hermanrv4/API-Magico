<?php

namespace App\Http\Common\Services;
use App\Http\Models\Database;
use Illuminate\Database\Eloquent\Model;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DatabaseService extends Model{
    public static function AddJSONSelect($principal_table,$query,$fields){
        return DatabaseService::AddJSONSelectByLang($principal_table,$query,LaravelLocalization::getCurrentLocale(),$fields);
    }
    public static function AddJSONSelectByLang($principal_table,$query,$lang,$fields){
        for($i=0;$i<count($fields);$i++){
            $query->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(".$principal_table.'.'.$fields[$i].", '$[0].".$lang."')) as '".$fields[$i]."_localized'");
            
        }
        return $query;
    }
    public static function AddJSONWhere($principal_table,$query,$fields,$values){
        return DatabaseService::AddJSONWhereByLang($principal_table,$query,LaravelLocalization::getCurrentLocale(),$fields,$values);
    }
    public static function AddJSONWhereByLang($principal_table,$query,$lang,$fields,$values){
        for($i=0;$i<count($fields);$i++){
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(".$principal_table.'.'.$fields[$i].", '$[0].".$lang."')) = ?",array(count($values)>$i?$values[$i]:NULL));
        }
        return $query;
    }
}

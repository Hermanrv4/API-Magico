<?php
namespace App\Http\Common\Helpers;

use Carbon\Carbon;
use DateTimeZone;

class DateHelper{

    public static function GetNow(){
        return Carbon::parse(Carbon::now(config('env.app_timezone')));
    }
    public static function GetDateFromString($date,$format=null){
        return Carbon::createFromFormat(StringHelper::IsNull($format,config("env.app_dateformat")),$date,config('env.app_timezone'));
    }
    public static function GetParsedDateFromDateTime($datetime){
        return Carbon::parse($datetime,config('env.app_timezone'));
    }
    public static function AddDaysToDate($date,$days_to_add,$only_weekdays){
        $count=0;
        if($date==null) $date = DateHelper::GetNow();
        while($count<$days_to_add){
            $date->addDay();
            if(!$only_weekdays){
                $count++;
            }elseif($date->isWeekday()){
                $count++;
            }
        }
        return $date;
    }
    public static function GetStringFromDate($date){
        try {
            date_default_timezone_set(config('env.app_timezone'));
            $dateTIME = Carbon::parse($date,config('env.app_timezone'));
            return trans(config('constants.lang_global').'date', ['prm1' => $dateTIME->format('d'), 'prm2' => DateHelper::GetMonthByPosition($dateTIME->format('m')), 'prm3' => $dateTIME->format('Y')]);
        }catch(\Exception $ex){
            return "";
        }
    }
    public static function GetMonthByPosition($pos){
        return trans(config('constants.lang_global').'month_'.strval($pos));
    }
}

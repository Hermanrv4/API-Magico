<?php

namespace App\Http\Modules\Admin\Extra\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends ApiController{
    public function Clear(){
        /*$type_unloca = config('app.value.db.type.parameter.unlocalized');
        $type_locali = config('app.value.db.type.parameter.localized');
        Parameter::QuickSave($type_unloca,'product_max_new_time_minutes','3600000',0);
        Parameter::QuickSave($type_unloca,'product_latest_quantity','10',0);*/
        //Artisan::call('config:cache');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('view:cache');
        Artisan::call('route:clear');
        Artisan::call('route:list');
        return dd(Artisan::output());
    }
    public function Migrate(){
        Artisan::call("migrate");
        Artisan::call("db:seed");
        return dd(Artisan::output());
    }
}

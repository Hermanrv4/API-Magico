<?php

namespace App\Http\Modules\Site\Extra\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends ApiController{
    public function Clear(){
        /*$type_unloca = config('app.value.db.type.parameter.unlocalized');
        $type_locali = config('app.value.db.type.parameter.localized');
        Parameter::QuickSave($type_unloca,'product_max_new_time_minutes','3600000');
        Parameter::QuickSave($type_unloca,'product_latest_quantity','10');*/
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
    public function UploadPhoto(Request $request){
        $base_path = 'resources/images/';
        $image_name = DateHelper::GetNow()->timestamp.'.png';

        $base=$request['image']; 
        $binary=base64_decode($base);
        header('Content-Type: bitmap; charset=utf-8');
        $file = fopen($base_path.$image_name, 'wb');
        fwrite($file, $binary);
        fclose($file);

        Return $this->SendSuccessResponse(null,array("image"=>config("env.app_url").$base_path.$image_name));
    }
    /******************************************************************************** */
    
}
  
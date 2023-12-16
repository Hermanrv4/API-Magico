<?php

namespace App\Http\Modules\Site\Extra\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Database\Parameter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class TestController extends ApiController{
    public function First($val){
        return $val;
    }
}

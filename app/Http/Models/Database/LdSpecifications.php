<?php

namespace App\Http\Models\Database;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LdSpecifications extends BaseModel
{
    protected $table = "ld_specifications";
    public $timestamps = false;
    protected $fillable = ['code','name','color','html','preview', 'image', 'gb_filter', 'need_user_info'];
}
<?php

namespace App\Http\Models\Database;
use App\Http\Common\Helpers\DateHelper;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LdCategory extends BaseModel
{
    protected $table = 'ld_categories';
    public $timestamps = false;
    protected $fillable = ['code','root_code','name','url_code','baner'];
    protected $string_json = ['name', 'url_code'];
}
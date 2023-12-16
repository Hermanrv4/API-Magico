<?php
namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Carbon\Carbon;
class Tracing extends BaseModel
{
    protected $table = 'tracings';
    protected $table_reference = 'users';
    protected $fillable = ['id_user', 'page_title', 'action', 'object', 'url_section', 'value', 'user_valid', 'sendobj'];
    protected $string_json = [];
    /* protected $hidden = ['created_at', 'updated_at']; */
    function scopeGetVisitPage($query){
        return $query->select(
            (new static)->getTable().'.page_title as section',
            /* (new static)->getTable().'.url_section', */
            DB::raw(" count(".(new static)->getTable().".page_title) as count_section")
        )->where(
            (new static)->getTable().'.action', "=", 'load',
        )->groupBy(
            (new static)->getTable().'.page_title',
            /* (new static)->getTable().'.url_section' */
        );
    }
    function scopeGetPreviewProductCount($query){
        return $query->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            (new static)->getTable().'.sendobj',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_product"),
            /* (new Product)->getTable().'.name', */
            (new Product)->getTable().'.sku',
            DB::raw(" count(".(new Product)->getTable().".sku) as count_preview ")
        )->where(
            (new static)->getTable().'.object', "=", "preview"
        )->groupBy(
            (new Product)->getTable().'.sku',
            (new static)->getTable().'.sendobj',
            (new Product)->getTable().'.name',
        );
    }
    function scopeGetAddCardProductCount($query){
        return $query->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            (new static)->getTable().'.sendobj',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_product"),
            /* (new Product)->getTable().'.name', */
            (new Product)->getTable().'.sku',
            DB::raw(" count(".(new Product)->getTable().".sku) as count_addcard ")
        )->where(
            (new static)->getTable().'.object', "=", "addCard"
        )->groupBy(
            (new Product)->getTable().'.sku',
            (new static)->getTable().'.sendobj',
            (new Product)->getTable().'.name',
        );
    }
    public function scopeGetCountVisitUserIsNull($query){
        return $query->select(
            (new static)->getTable().'.page_title',
            DB::raw("count(".(new static)->getTable().".url_section) as count_vist"),
        )->where(
            (new static)->getTable().'.user_valid', "=", 0
        )->where(
            (new static)->getTable().'.action', "=", "load"
        )->groupBy(
            (new static)->getTable().'.page_title'
        );
    }
    public function scopeGetCategoryVisit($query){
        return $query->join( (new Category)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.value' )
        ->select(
            (new static)->getTable().'.value',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as category_name"),
            DB::raw("count(".(new static)->getTable().".value) as count_category")
        )->where(
            (new static)->getTable().'.action', "=","load"
        )->groupBy(
            (new static)->getTable().'.value',
            (new Category)->getTable().'.name',
            (new Category)->getTable().'.url_code',
        );
    }
    public function scopeGetCategoryVisitUserNull($query){
        return $query->join( (new Category)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.value' )
        ->select(
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as category_name"),
            DB::raw("count(".(new static)->getTable().".value) as count_vist"),
        )->where(
            (new static)->getTable().'.user_valid', "=", 0
        )->where(
            (new static)->getTable().'.action', "=", "load"
        )->groupBy(
            (new static)->getTable().'.value',
            (new Category)->getTable().'.name'
        );
    }
    //date
    public function scopeGetVisitPageOfDate($query, $date_start, $date_end){
        return $query->select(
            (new static)->getTable().'.page_title as section',
            DB::raw(" count(".(new static)->getTable().".url_section) as count_section")
        )->where(
            (new static)->getTable().'.action', "=", 'load',
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.page_title',
        );
    }
    function scopeGetPreviewProductCountOfDate($query, $date_start, $date_end){
        return $query->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            (new static)->getTable().'.sendobj',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_prod"),
            (new Product)->getTable().'.sku',
            DB::raw(" count(".(new Product)->getTable().".sku) as count_preview ")
        )->where(
            (new static)->getTable().'.object', "=", "preview"
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new Product)->getTable().'.sku',
            (new static)->getTable().'.sendobj',
            (new Product)->getTable().'.name',
        );
    }
    function scopeGetAddCardProductCountOfDate($query, $date_start, $date_end){
        return $query->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            (new static)->getTable().'.sendobj',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_prod"),
            (new Product)->getTable().'.sku',
            DB::raw(" count(".(new Product)->getTable().".sku) as count_addcard ")
        )->where(
            (new static)->getTable().'.object', "=", "addCard"
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new Product)->getTable().'.sku',
            (new static)->getTable().'.sendobj',
            (new Product)->getTable().'.name',
        );
    }
    public function scopeGetCategoryVisitOfDate($query, $date_start, $date_end){
        return $query->join( (new Category)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.value' )
        ->select(
            (new static)->getTable().'.value',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as category_name"),
            DB::raw("count(".(new static)->getTable().".value) as count_category")
        )->where(
            (new static)->getTable().'.action', "=","load"
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.value',
            (new Category)->getTable().'.name',
            (new Category)->getTable().'.url_code',
        );
    }
    public function scopeGetCountVisitUserIsNullOfDate($query, $date_start, $date_end){
        return $query->select(
            (new static)->getTable().'.page_title',
            DB::raw("count(".(new static)->getTable().".page_title) as count_vist"),
        )->where(
            (new static)->getTable().'.user_valid', "=", 0
        )->where(
            (new static)->getTable().'.action', "=", "load"
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.page_title'
        );
    }
    //paginas mas visitadas por usuarios
    public function scopeGetCountVisitPageOfUser($query, $id_user){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as name_user"),
            (new static)->getTable().'.page_title as section',
            (new static)->getTable().'.action',
            DB::raw(" count(".(new static)->getTable().".page_title) as count_section")   
        )->where(
            (new static)->getTable().'.action', "=", "load"
        )->where(
            (new static)->getTable().'.object', "=", "show"
        )->where(
            (new static)->getTable().'.id_user',"=", $id_user
        )/* ->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse()->startOfDay(), Carbon::parse()->endOfDay()]
        ) */->groupBy(
            (new static)->getTable().'.page_title',
            (new static)->getTable().'.action',
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name'
        );
    }
    public function scopeGetCountPreviewOfUser($query, $id_user){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as name_user"),
            (new static)->getTable().'.sendobj',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_prod"),
            (new Product)->getTable().'.sku',
            DB::raw(" count(".(new static)->getTable().".sendobj) as count_prod ")
        )->where(
            (new static)->getTable().'.object',"=", "preview"
        )->where(
            (new static)->getTable().'.id_user', "=", $id_user
        )->groupBy(
            (new static)->getTable().'.sendobj',
            (new Product)->getTable().'.sku',
            (new Product)->getTable().'.name',
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name'
        );
    }
    public function scopeGetCountAddCardOfUser($query, $id_user){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as name_user"),
            (new static)->getTable().'.sendobj',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_prod"),
            (new Product)->getTable().'.sku',
            DB::raw(" count(".(new static)->getTable().".sendobj) as count_prod ")
        )->where(
            (new static)->getTable().'.object',"=", "addCard"
        )->where(
            (new static)->getTable().'.id_user', "=", $id_user
        )->groupBy(
            (new static)->getTable().'.sendobj',
            (new Product)->getTable().'.sku',
            (new Product)->getTable().'.name',
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name'
        );
    }
    public function scopeGetCategoryVisitForUser($query, $id_user){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Category)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.value' )
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as name_user"),
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as category_name"),
            (new Category)->getTable().'.name',
            (new static)->getTable().'.value',
            DB::raw("count(".(new static)->getTable().".value) as count_value")
        )->where(
            (new static)->getTable().'.object', "=", "show"
        )->where(
            (new static)->getTable().'.action', "=", 'load'
        )->where(
            (new static)->getTable().'.id_user', "=", $id_user
        )->groupBy(
            (new static)->getTable().'.value',
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name',
            (new Category)->getTable().'.name',
        );
    }
    // date user
    public function scopeGetCountVisitPageOfUserForDate($query, $id_user, $date_start, $date_end){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as name_user"),
            (new static)->getTable().'.page_title as section',
            (new static)->getTable().'.action',
            DB::raw(" count(".(new static)->getTable().".page_title) as count_section")   
        )->where(
            (new static)->getTable().'.action', "=", "load"
        )->where(
            (new static)->getTable().'.object', "=", "show"
        )->where(
            (new static)->getTable().'.id_user',"=", $id_user
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.page_title',
            (new static)->getTable().'.action',
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name'
        );
    }
    public function scopeGetCountPreviewOfUserForDate($query, $id_user, $date_start, $date_end){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as name_user"),
            (new static)->getTable().'.sendobj',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_prod"),
            (new Product)->getTable().'.sku',
            DB::raw(" count(".(new static)->getTable().".sendobj) as count_prod ")
        )->where(
            (new static)->getTable().'.object',"=", "preview"
        )->where(
            (new static)->getTable().'.id_user', "=", $id_user
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.sendobj',
            (new Product)->getTable().'.sku',
            (new Product)->getTable().'.name',
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name'
        );
    }
    public function scopeGetCountAddCardOfUserForDate($query, $id_user, $date_start, $date_end){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as name_user"),
            (new static)->getTable().'.sendobj',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_prod"),
            (new Product)->getTable().'.sku',
            DB::raw(" count(".(new static)->getTable().".sendobj) as count_prod ")
        )->where(
            (new static)->getTable().'.object',"=", "addCard"
        )->where(
            (new static)->getTable().'.id_user', "=", $id_user
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.sendobj',
            (new Product)->getTable().'.sku',
            (new Product)->getTable().'.name',
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name'
        );
    }
    public function scopeGetCategoryVisitForUserForDate($query, $id_user, $date_start, $date_end){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Category)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.value' )
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as name_user"),
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as category_name"),
            (new Category)->getTable().'.name',
            (new static)->getTable().'.value',
            DB::raw("count(".(new static)->getTable().".value) as count_value")
        )->where(
            (new static)->getTable().'.object', "=", "show"
        )->where(
            (new static)->getTable().'.action', "=", 'load'
        )->where(
            (new static)->getTable().'.id_user', "=", $id_user
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        )->groupBy(
            (new static)->getTable().'.value',
            (new User)->getTable().'.first_name',
            (new User)->getTable().'.last_name',
            (new Category)->getTable().'.name',
        );
    }
    //detail user
    public function scopeGetDataFilterAction($query, $date_start, $date_end){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj' )
        ->join( (new Category)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.value' )
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as Nombres_y_Apellidos "),
            (new static)->getTable().'.page_title as Pagina_Web',
            (new static)->getTable().'.url_section as Seccion',
            DB::raw(" if(".(new static)->getTable().".object = 'show', 'El Usuario visito la pagina', if(".(new static)->getTable().".object = 'preview', 'El usuario Previsualizo un producto', 'El usuario agrego un producto a su carrito de compras') ) as Objectivo_de_interaccion "),
            DB::raw(" if(".(new static)->getTable().".action = 'load', 'Pagina web cargada', 'Click en una seccion de la web') as Accion_Realizada "),
            (new static)->getTable().'.value as lugar_de_interaccion',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as category_name"),
            (new static)->getTable().'.sendobj as url_product',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as product_name"),
            (new static)->getTable().'.created_at as Fecha_hora_de_interaccion'
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    public function scopeGetDataFilterVisit($query, $id_user , $date_start, $date_end){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->select(
            DB::raw(" concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as Nombre_Completo "),
            (new static)->getTable().'.action as Accion_Realizada',
            (new static)->getTable().'.page_title as Pagina_Web',
            (new static)->getTable().'.url_section as Seccion',
            (new static)->getTable().'.value as Lugar_de_interaccion',
            (new static)->getTable().'.object as Objetivo_de_interaccion',
            (new static)->getTable().'.created_at'
        )->where(
            (new static)->getTable().'.id_user','=', $id_user
        )->where(
            (new static)->getTable().'.action', "=", 'load'
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    public function scopeGetDataFilterPreview($query, $id_user, $date_start, $date_end){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            DB::raw("concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as Full_name"),
            (new static)->getTable().'.page_title as Pagina_Web',
            (new static)->getTable().'.url_section as Seccion',
            (new static)->getTable().'.action as Accion_Realizada',
            (new static)->getTable().'.value as Lugar_de_interaccion',
            (new static)->getTable().'.object as Objetivo_de_interaccion',
            (new static)->getTable().'.sendobj as Url_Prod',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_prod"),
            (new Product)->getTable().'.sku as Sku_prod',
            (new static)->getTable().'.created_at as Fecha_Hora'
        )->where(
            (new static)->getTable().'.object',"=", "preview"
        )->where(
            (new static)->getTable().'.id_user', "=", $id_user
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    public function scopeGetDataFilterAddCard($query, $id_user, $date_start, $date_end){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Product)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.sendobj')
        ->select(
            DB::raw("concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as Full_name"),
            (new static)->getTable().'.page_title as Pagina_Web',
            (new static)->getTable().'.url_section as Seccion',
            (new static)->getTable().'.action as Accion_Realizada',
            (new static)->getTable().'.value as Lugar_de_interaccion',
            (new static)->getTable().'.object as Objetivo_de_interaccion',
            (new static)->getTable().'.sendobj as Url_Prod',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Product)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_prod"),
            (new Product)->getTable().'.sku as Sku_prod',
            (new static)->getTable().'.created_at as Fecha_Hora'
        )->where(
            (new static)->getTable().'.object',"=", "addCard"
        )->where(
            (new static)->getTable().'.id_user', "=", $id_user
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    public function scopeGetDataFilterVisitCategories($query, $id_user, $date_start, $date_end){
        return $query->join( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join( (new Category)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.value' )
        ->select(
            DB::raw("concat(".(new User)->getTable().".first_name, ' ', ".(new User)->getTable().".last_name) as Full_name"),
            (new static)->getTable().'.page_title as Pagina_Web',
            (new static)->getTable().'.action as Accion_Realizada',
            (new static)->getTable().'.url_section as Seccion',
            (new static)->getTable().'.value as Lugar_de_interaccion',
            (new static)->getTable().'.object as Objetivo_de_interaccion',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as categoria_visitada"),
            (new static)->getTable().'.created_at as Fecha_Hora'
        )->where(
            (new static)->getTable().'.action', "=", 'load'
        )->where(
            (new static)->getTable().'.id_user', "=", $id_user
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    //detail
    public function scopeGetDataFilterVisitOfDate($query, $date_start, $date_end){
        return $query->leftjoin( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->select(
            DB::raw(" if(".(new static)->getTable().".user_valid=0, 'Usuario no registrado', ".(new User)->getTable().".first_name) as name_user "),
            (new static)->getTable().'.page_title as Pagina_Web',
            (new static)->getTable().'.url_section',
            (new static)->getTable().'.action as Accion_realizada',
            (new static)->getTable().'.value as Lugar_de_interracion',
            (new static)->getTable().'.object as Objetivo_de_interaccion',
            (new static)->getTable().'.created_at'
        )->where(
            (new static)->getTable().'.action', "=", 'load'
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    public function scopeGetDataFilterPreviewOfDate($query, $date_start, $date_end){
        return $query->leftjoin( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->select(
            DB::raw(" if(".(new static)->getTable().".user_valid=0, 'Usuario no registrado', ".(new User)->getTable().".first_name) as name_user "),
            (new static)->getTable().'.page_title as Pagina_Web',
            (new static)->getTable().'.url_section',
            (new static)->getTable().'.action as Accion_realizada',
            (new static)->getTable().'.value as Lugar_de_interracion',
            (new static)->getTable().'.object as Objetivo_de_interaccion',
            (new static)->getTable().'.sendobj as objetivo_de_interaccion',
            (new static)->getTable().'.created_at as Fecha_de_interaccion'
        )->where(
            (new static)->getTable().'.action', "=", 'click'
        )->where(
            (new static)->getTable().'.object', "=", 'preview'
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    public function scopeGetDataFilterAddCardOfDate($query, $date_start, $date_end){
        return $query->leftjoin( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->select(
            DB::raw(" if(".(new static)->getTable().".user_valid=0, 'Usuario no registrado', ".(new User)->getTable().".first_name) as name_user "),
            (new static)->getTable().'.page_title as Pagina_Web',
            (new static)->getTable().'.url_section',
            (new static)->getTable().'.action as Accion_realizada',
            (new static)->getTable().'.value as Lugar_de_interracion',
            (new static)->getTable().'.object as Objetivo_de_interaccion',
            (new static)->getTable().'.sendobj',
            (new static)->getTable().'.created_at'
        )->where(
            (new static)->getTable().'.action', "=", 'click'
        )->where(
            (new static)->getTable().'.object', "=", 'addCard'
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    public function scopeGetDataFilterVisitCategory($query, $date_start, $date_end){
        return $query->leftjoin( (new User)->getTable(), (new User)->getTable().'.id', "=", (new static)->getTable().'.id_user' )
        ->join((new Category)->getTable(), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".url_code, '$[0].".LaravelLocalization::getCurrentLocale()."'))"), "=", (new static)->getTable().'.value')
        ->select(
            DB::raw(" if(".(new static)->getTable().".user_valid=0, 'Usuario no registrado', ".(new User)->getTable().".first_name) as name_user "),
            (new static)->getTable().'.page_title as Pagina_Web',
            (new static)->getTable().'.url_section',
            (new static)->getTable().'.action as Accion_realizada',
            (new static)->getTable().'.value as Lugar_de_interracion',
            DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Category)->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) category_name"),
            (new static)->getTable().'.object as Objetivo_de_interaccion',
            (new static)->getTable().'.sendobj',
            (new static)->getTable().'.created_at'
        )->where(
            (new static)->getTable().'.action', "=", 'load'
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_end)->endOfDay()]
        );
    }
    //count wsp
    public function scopeGetCountWsp($query){
        return $query->select(
            (new static)->getTable().'.object',
            DB::raw("count(".(new static)->getTable().".object) as count_object")
        )->where(
            (new static)->getTable().'.object', "=", 'contact_wsp' 
        )->groupBy(
            (new static)->getTable().'.object'
        );
    }
    public function scopeGetCountWspOfDate($query, $date_start){
        return $query->select(
            (new static)->getTable().'.object',
            DB::raw("count(".(new static)->getTable().".object) as count_object")
        )->where(
            (new static)->getTable().'.object', "=", 'contact_wsp' 
        )->WhereBetween(
            (new static)->getTable().'.created_at', [Carbon::parse($date_start)->startOfDay(), Carbon::parse($date_start)->endOfDay()]
        );
    }
    public function scopeGetCountMessenger($query){
        return $query->select(
            (new static)->getTable().'.object',
            DB::raw("count(".(new static)->getTable().".object) as count_object")
        )->where(
            (new static)->getTable().'.object', "=", 'contact_messenger' 
        )->groupBy(
            (new static)->getTable().'.object'
        );
    }
}
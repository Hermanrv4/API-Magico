<?php

namespace App\Http\Models\Database;
use App\Http\Models\Base\BaseModel;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Common\Services\DatabaseService;
use Carbon\Carbon;
use App\Http\Models\Database\Currency;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Type;
use App\Http\Common\Helpers\DateHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Discount extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'discounts';
    protected $table_reference = '';
    protected $fillable = ['code', 'name', 'description', 'currency_id', 'value', 'affectation', 'free_shipping', 'is_acumulate', 'max_uses', 'acumulate_uses', 'id_type_discounts', 'id_cards', 'date_start', 'date_end'];
    protected $string_json = ['name', 'description'];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>
    // botted
    protected static function booted()
    {
        parent::boot();
        static::addGlobalScope('GetAllDiscount', function(Builder $builder){
            $builder
            ->leftjoin( (new Type())->getTable(), (new Type())->getTable().'.id', "=", (new static)->getTable().'.id_type_discounts' )
            ->leftjoin( (new Currency())->getTable(), (new Currency())->getTable().'.id', "=", (new static)->getTable().'.currency_id')
            ->select(
                (new static)->getTable().'.id',
                (new static)->getTable().'.code',
                (new static)->getTable().'.name',
                (new static)->getTable().'.description',
                (new static)->getTable().'.currency_id',
                (new Currency())->getTable().'.name as name_currencies',
                (new Currency())->getTable().'.symbol as symbol',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Currency())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_currencies_localized"),
                (new static)->getTable().'.value',
                (new static)->getTable().'.affectation',
                (new static)->getTable().'.free_shipping',
                (new static)->getTable().'.is_acumulate',
                (new static)->getTable().'.max_uses',
                (new static)->getTable().'.acumulate_uses',
                (new static)->getTable().'.id_type_discounts',
                (new Type())->getTable().'.name as name_types',
                (new Type())->getTable().'.code as code_types',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(".(new Type())->getTable().".name, '$[0].".LaravelLocalization::getCurrentLocale()."')) as name_types_localized"),
                (new static)->getTable().'.id_cards',
                (new static)->getTable().'.date_start',
                (new static)->getTable().'.date_end',
            );
            DatabaseService::AddJSONSelect((new static())->getTable(), $builder,(new static)->getStringJSON());
        });
    }
    // funcion para obtener la lista de cards

    public static function ValidatDiscount($id){
        $discount = Discount::where( (new static)->getTable().'.date_end', ">" , now() )
        ->where( (new static)->getTable().'.date_start', "<" , now() )
        ->where( (new static)->getTable().'.id', "=" ,$id )->first();

        if($discount->is_acumulate == 1){
            if($discount->max_uses<=$discount->acumulate_uses){
                $discount = null;
            }
        }

        return $discount;   
    }
    public static function ValidatCoupon($code,$cardsInfo=null,$type_discount){
        $discount = Discount::where( (new static)->getTable().'.date_end', ">" , now() )
        ->where( (new static)->getTable().'.date_start', "<" , now() )
        ->where( (new static)->getTable().'.id_type_discounts', "!=" , $type_discount )
        ->where( (new static)->getTable().'.code', "=" ,$code )->first();
        if($discount!=null){
            $discount->card_info = null;
            if($discount->id_cards != null || $discount->id_cards != ''){
                /* if($discount->currency_id!=null){ */
                    if($cardsInfo!=null){
                        for($j=0;$j<count($cardsInfo);$j++){
                            if($discount->id_cards == $cardsInfo[$j]->code){
                                $discount->card_info = $cardsInfo[$j];
                            }
                        }
                    }
                /* } */
            }
            if($discount->is_acumulate == 1){
                if($discount->max_uses<=$discount->acumulate_uses){
                    $discount = null;
                }
            }
           
        }
        return $discount;
    }
    public static function GetByIdCardType($id_cards,$type){
        $discount = Discount::where( (new static)->getTable().'.date_end', ">" , now() )
        ->where( (new static)->getTable().'.date_start', "<" , now() )
        ->where( (new static)->getTable().'.id_type_discounts', "=" ,$type)
        ->where( (new static)->getTable().'.id_cards', "=" ,$id_cards )->first();

        return $discount;   
    }
    public static function GetAllDiscounts($cupon_param,$card_param,$cardsInfo){
        $all = Discount::where( (new static)->getTable().'.date_end', ">" , now() )
        ->where( (new static)->getTable().'.date_start', "<" , now() )->get();
        //$Cupon_ = Type::GetByCode($cupon_param);
        $Card_ = Type::GetByCode($card_param);

        $discounts = array();
        $cards = array();
        $coupon = array();
        for($i=0;$i<count($all);$i++){
            $all[$i]->card_info = null;
            $all[$i]->currency_info = null;

                for($j=0;$j<count($cardsInfo);$j++){
                    if($all[$i]->id_cards == $cardsInfo[$j]->code){
                        $all[$i]->card_info = $cardsInfo[$j];
                    }
                }
                
                if($all[$i]->currency_id!=null){ 
                    $all[$i]->currency_info = Currency::GetById($all[$i]->currency_id);
                }

                if($all[$i]->is_acumulate == 1){
                    if($all[$i]->acumulate_uses < $all[$i]->max_uses){
                        if($all[$i]->id_type_discounts == $Card_->id){
                            $cards[] = $all[$i];
                        }else{
                            $coupon[] = $all[$i];
                        }
                    }
                }else{
                    if($all[$i]->id_type_discounts == $Card_->id){
                        $cards[] = $all[$i];
                    }else{
                        $coupon[] = $all[$i];
                    }
                }
        }
        $discounts = array('cards'=>$cards,'coupon'=>$coupon);
        return $discounts;
    }
    public static function PlusDiscount($id){ 
        $discount = Discount::where( (new static)->getTable().'.date_end', ">" , now() )
        ->where( (new static)->getTable().'.date_start', "<" , now() )
        ->where( (new static)->getTable().'.id', "=" ,$id )->first();
        $uses = $discount->acumulate_uses;
        if($discount->is_acumulate == "1" || $discount->is_acumulate == 1){
            if($discount->max_uses >= ($uses + 1)){
                $discount->acumulate_uses = $discount->acumulate_uses + 1;
                $discount->save();
            }
        }
        return $discount;
    }
} 

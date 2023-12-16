<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationLdProducts;
use App\Http\Models\Database\LdProducts;
use Illuminate\Support\Facades\DB;

class LdProductController extends ApiController{
    public function Register(Request $request){
        $msg_validation=null;
        $value=self::convertArray($request["data"]);
        try{
            for($i=0; $i<count($value[0]); $i++){
                $validator=ValidationLdProducts::LdProductsRegister($value[0][0], $msg_validation);
                if($validator->fails()){
                    return $this->SendErrorResponse(array(), array($validator->errors()));
                    $i=count($request[1]);
                    break;
                }
            }
        }catch(Exception $e){
            return $this->SendErrorResponse(array(), array("data"=>$e));
        }
        
        //instaciamos el modelo
        try{
            LdProducts::truncate();
            for($a=0; $a<count($value[0]); $a++){
                $ldproducObject=new LdProducts();
                $ldproducObject->category_code      =   $value[0][$a]["category_code"];
                $ldproducObject->group_id           =   $value[0][$a]["group_id"];
                $ldproducObject->product_group      =   $value[0][$a]["product_group"];
                $ldproducObject->product            =   $value[0][$a]["product"];
                $ldproducObject->url_code           =   $value[0][$a]["url_code"];
                $ldproducObject->description        =   $value[0][$a]["description"];
                $ldproducObject->sku                =   $value[0][$a]["sku"];
                $ldproducObject->is_catalogue       =   $value[0][$a]["catalogo"];
                $ldproducObject->stock              =   $value[0][$a]["stock"];
                $ldproducObject->regular_price      =   $value[0][$a]["precio_regular"];
                $ldproducObject->online_price       =   $value[0][$a]["precio_online"];
                $ldproducObject->photos             =   $value[0][$a]["photo"];
                $ldproducObject->especifications    =   $value[0][$a]["especifications"];
                $ldproducObject->save();
            }
        }catch(Exception $e){
            return $this->SendErrorResponse(array(), array("error"=>$e));
        }
        return $this->SendSuccessResponse(trans($msg_validation.'form_result.success'), array("result"=>$ldproducObject));
    }
    public static function convertArray($obj) : array{
        $value=array();
        $array= (array) json_decode($obj, true);
        for($i=0; $i<count($array); $i++){
            $value[]=$array[$i];
        }
        return $value;
    }
    public function Get(Request $request){
        return $this->SendSuccessResponse(null, LdProducts::all());
    }
    public function ExeProcedure(Request $request){
        DB::select('SET SQL_SAFE_UPDATES=0');
        return $this->SendSuccessResponse(null, DB::select('call usp_ld_load_products(:param)', ["param"=>'NO']));
    }
}
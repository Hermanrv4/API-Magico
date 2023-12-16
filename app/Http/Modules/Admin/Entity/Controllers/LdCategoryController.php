<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Modules\Admin\Services\ValidationLdCategory;
use App\Http\Models\Database\LdCategory;
class LdCategoryController extends ApiController{
    public function Register(Request $request){
        $msg_validation=null;
        $value=self::convertArray($request["data"]);
        for($i=0; $i<count($value[0]); $i++){
            $validator=ValidationLdCategory::LdCategoryRegister($value[0][$i], $msg_validation);
            if($validator->fails()){
                return $this->SendErrorResponse(array(), array("data"=>$value[0][$i], "error"=>$validator->errors()) );
                $i=count($value[0]);
                break;
            }
        }
        LdCategory::truncate();
        for($a=0; $a<count($value[0]); $a++){
            try{
                $ldCategoriesObject=new LdCategory();
                $ldCategoriesObject->code       =   $value[0][$a]["code"];
                $ldCategoriesObject->root_code  =   $value[0][$a]["root_code"] ?? "";
                $ldCategoriesObject->name       =   $value[0][$a]["name"];
                $ldCategoriesObject->url_code   =   $value[0][$a]["url_code"];
                $ldCategoriesObject->baner      =   $value[0][$a]["banner"];
                $ldCategoriesObject->save();
            }catch(Exception $e){
                return $this->SendErrorResponse(array(), array("error"=>$e));
            }
        }
        return $this->SendSuccessResponse(trans($msg_validation.'form_result.success'), array("result"=>$value));
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
        return $this->SendSuccessResponse(array(), LdCategory::all());
    }
}
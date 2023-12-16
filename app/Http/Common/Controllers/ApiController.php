<?php

namespace App\Http\Common\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Common\Responses;

class ApiController extends Controller {
    public function SendSuccessResponse($message=null,$response=null){
        return (new Responses\ApiResponse())->SendResponse(true,$message,$response);
    }
    public function SendErrorResponse($message=null,$response=null,$locale=null){
        return (new Responses\ApiResponse())->SendResponse(false,$message,$response);
    }
	public function LocalizationArray($array='[]'){
        $string_json=\json_decode($array);
        $lang=config('laravellocalization.localesOrder');
        if(count($string_json)>0){
            $list=[];
            for($item=0; $item<count($string_json); $item++){
                $list=(array) $string_json[$item];
            }
            if(count($list)<count($lang)){
                for($b=0; $b<count($lang); $b++){
                    $list[$lang[$b]]=$list[$lang[$b]]?? '';
                }
            }
            // obtener el valor por defecto
            $value=array_filter($list, function($item){
                if($item!=""){
                    return $item;
                }
            });
            if(count($value)>0){
                $keys_value=array_keys($value);
                $keys=array_keys($list);
                for($item=0; $item<count($keys); $item++){
                    $list[$keys[$item]]=($list[$keys[$item]]=="" ? $value[$keys_value[0]]: $list[$keys[$item]]);
                }
            }
            $list=(object) $list;
            return json_encode([$list]);
        }else{
            return "[{}]";
        }
    }
}

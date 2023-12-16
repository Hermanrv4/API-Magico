<?php
namespace App\Http\Common\Helpers;

use Carbon\Carbon;
use App\Http\Models\Database\Event;
use App\Http\Models\Database\Order;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\Ubication;

class StringHelper{

    public static function IsNull($obj,$default){
        return ($obj==null?$default:$obj);
    }
    public static function RandomString($len){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $len; $i++) {
            $randstring = $randstring.$characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }
	
	public static function GetTokenForNewEvent(){
        $RandomToken = StringHelper::RandomString(10);
        while(Event::ExistsWithToken($RandomToken)){
            $RandomToken = StringHelper::RandomString(10);
        }
        return $RandomToken;
    }

    public static function GetTrxPayCode(){
        $default_trans = Parameter::GetByCode('init_code_transfer');
        $RandomToken = $default_trans . StringHelper::RandomString(10);
     
        while(Order::ExistsTrxPayCode($RandomToken)){
            $RandomToken = $default_trans . StringHelper::RandomString(10);
        }
        return $RandomToken;
    }
    public static function CompleteStringUbication($address,$id){
        $text = $address;
        $objUbication = null;
        $objUbication = Ubication::GetById($id);
        $text = $text . " - ".$objUbication["name_localized"];

        while($objUbication!=null){
            $newU = Ubication::GetById($objUbication["root_ubication_id"]);
            $text = $text.", ".$newU["name_localized"];
            $objUbication = Ubication::GetById($newU["root_ubication_id"]);
        }
        
        return $text;
    }
    public static function removeAccents($cadena) {
        $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }
}

<?php

namespace App\Http\Modules\Site\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\Cluster;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\ProductSpecification;
use App\Http\Models\Database\Specification;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SpecificationController extends ApiController{
    public function Get(Request $request){
        if(isset($request['categories'])){
            return $this->SendSuccessResponse(null, Specification::GetFilterSpecification($request["categories"]));
        }else{
            return $this->SendSuccessResponse(null,Specification::all());
        }
    } 
    public function GetByCode(Request $request){
        return $this->SendSuccessResponse(null,Specification::GetByCode($request["code"]));
    }
    public function GetValuesByCategoriesAndSpecificationId(Request $request){ 
		$data = ProductSpecification::GetValuesByCategoriesAndSpecificationId(str_replace(Parameter::GetByCode('db_query_union'),",",$request["categories"]),$request["specification_id"]);
        $lg =   LaravelLocalization::getCurrentLocale();

        $temp = array();
        $new = array();
        foreach($data as $valueObj){
            $value = (array) $valueObj;
            if(!in_array($value["value"],$temp)){
                $temp[] = $value["value"];
                $lang = json_decode($value["value"],true);
                
                foreach($lang as $ke => $va)
                {
                    foreach($va as $key => $val)
                    {
                        if($key==$lg){
                            $value["value_localized"] = $val;
                        }
                    }
                }
                $new[] = $value;
            }
        }
		
        return $this->SendSuccessResponse(null,$new);
    }

    public function GetSpecificationValuesBySpecificationId(Request $request){
        $categoryName = strtoupper($request["categoryName"]);
        $category = Category::GetCategoryByName($categoryName);

        $specifications =  Specification::GetAllSpecifications();

        $currentSpecificationsValues = array();
        $auxSpecificationValue = "";

        foreach($specifications as $specificationsRow){                        
            $specificationValues = ProductSpecification::GetValuesByCategoriesAndSpecificationId($category[0]->id,$specificationsRow->id);

            $currentSpecifications = array();
            foreach($specificationValues as $specificationValuesRow){

                if($auxSpecificationValue !== $specificationValuesRow->value){
                    $auxSpecificationValue = $specificationValuesRow->value;

                    array_push($currentSpecifications, $specificationValuesRow->value);
                }
            }
            $specificationsRow->values = $currentSpecifications;
            array_push($currentSpecificationsValues, $specificationsRow);
        }
        
        return $this->SendSuccessResponse(null,$currentSpecificationsValues);
    }
    
    public function GetByIds(Request $request){

        $data = Specification::GetByIdSpecifications($request["ids"]);
        $lg =   LaravelLocalization::getCurrentLocale();
        
        $temp = array();
        $new = array();
        foreach($data as $valueObj){
            $value = (array) $valueObj;
            if(!in_array($value["name"],$temp)){
                $temp[] = $value["name"];
                $lang = json_decode($value["name"],true);
                
                foreach($lang as $ke => $va)
                {
                    foreach($va as $key => $val)
                    {
                        if($key==$lg){
                            $value["value_localized"] = $val;
                        }
                    }
                }
                $new[] = $value;
            }
        }

        return $this->SendSuccessResponse(null,$new);
    } 
    
}

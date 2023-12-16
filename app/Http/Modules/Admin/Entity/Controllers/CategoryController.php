<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Category;
use App\Http\Models\Database\CategorySpecification;
use App\Http\Models\Database\ProductGroup;
use App\Http\Models\Database\Parameter;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationCategory;

class CategoryController extends ApiController{

    public function Get(Request $request){
        if (isset($request["category_id"])) {
            return $this->SendSuccessResponse(null,Category::GetById($request["category_id"]));
        }else if(isset($request['category_menu'])){
            return $this->SendSuccessResponse(null, Category::GetByMenuCategory());
        }else{
            return $this->SendSuccessResponse(null,Category::all());
        }
    }



    public function GetByRoot(Request $request)
    {
        if($request["root_category_id"]==Parameter::GetByCode('default_id')){
            return $this->SendSuccessResponse(null,Category::GetRootParents());
        }else{
            return $this->SendSuccessResponse(null,Category::GetChildsByRoot($request["root_category_id"]));
        }
    }

    public function GetChildsByRoot(Request $request){
        return $this->SendSuccessResponse(null,Category::GetChildsByRoot($request["root_id"]));
    }
    
    public function Register(Request $request){
        try {
            $msg_validation = null;
            $validator = ValidationCategory::CategoryRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else {
                $objCategory = new Category();                
                $is_update = $request["id"]!=Parameter::GetByCode("default_id");
                if($is_update) $objCategory = Category::GetById($request['id']);
                if($request['root_category_id']==Parameter::GetByCode("default_id")){ $objCategory->root_category_id = null;}
                else{$objCategory->root_category_id = $request["root_category_id"];}
                $objCategory->url_code=$this->LocalizationArray($request['url_code']);
                $objCategory->code = $request['code'];
                $objCategory->name = $this->LocalizationArray($request['name']);
                $objCategory->banner = $request['banner'];
                $objCategory->show_menu = intval($request["show_menu"]);
                $objCategory->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objCategory));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function Delete(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationCategory::CategoryDelete($request,$msg_validation);
            $validator->after(function($validator) use ($request,$msg_validation){
                if (count(Category::GetChildsByRoot($request['id']))>0) $validator->errors()->add('form',trans($msg_validation.'form.exist_categories'));
                if(count(CategorySpecification::GetByTableId(Category::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_categoryspecification'));
                if(count(ProductGroup::GetByTableId(Category::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exist_productgroup'));
            });
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'));
            }else {
                $objCategory = Category::GetById($request['id']);
                $objCategory->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objCategory));
            }
        } catch (\Throwable $th) {
            if(config('env.app_debug'))return $this->SendErrorResponse($th->getMessage());
            else return $this->SendErrorResponse();
        }
    }
    public function GetCategoriesOrderBilling(Request $request){
        return $this->SendSuccessResponse(null, Category::withoutGlobalScopes()->GetCategorieOrderBillingDate($request["date_start"], $request["date_end"])->get());
    }
}

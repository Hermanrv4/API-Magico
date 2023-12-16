<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\Specification;
use App\Http\Models\Database\CategorySpecification;
use App\Http\Models\Database\ProductSpecification;
use App\Http\Models\Database\Parameter;
use Illuminate\Http\Request;
use App\Http\Modules\Admin\Services\ValidationSpecification;

class SpecificationController extends ApiController{
    
    public function Get(Request $request)
    {
        if (isset($request['specification_id'])) {
            return $this->SendSuccessResponse(null,Specification::GetById($request['specification_id']));
        }
        if (isset($request['specification_code'])) {
            return $this->SendSuccessResponse(null,Specification::GetByCodeSpecification($request['specification_code']));
        }else if(isset($request['specificacion_filter'])){
            return $this->SendSuccessResponse(null, Specification::where( (new Specification)->getTable().'.is_global_filter', '=', 1 )->get());
        }else{
            return $this->SendSuccessResponse(null,Specification::all());
        }
    }

    public function Register(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationSpecification::SpecificationRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objSpecification = new Specification();
                $is_update = $request['id']!=Parameter::GetByCode('default_id');
                if ($is_update) $objSpecification = Specification::GetById($request['id']);
                $objSpecification->code = $request['code'];
                $objSpecification->name =$this->LocalizationArray($request['name']);
                $objSpecification->is_preview = $request['is_preview'];
                $objSpecification->is_color = $request['is_color'];
                $objSpecification->is_html = $request['is_html'];
                $objSpecification->is_image = $request['is_image'];
               /*  $objSpecification->is_global_filter = $request['is_global_filter']; */
                $objSpecification->needs_user_info = $request['needs_user_info'];
                $objSpecification->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objSpecification));
            }
        } catch (\Exception $ex) {
            if (config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }

    public function Delete(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationSpecification::SpecificationDelete($request,$msg_validation);
            $validator->after(function($validator) use ($request,$msg_validation){
                if (count(CategorySpecification::GetByTableId(Specification::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exists_categoryspecification'));
                if (count(ProductSpecification::GetByTableId(Specification::class,$request['id']))>0)$validator->errors()->add('form',trans($msg_validation.'form.exists_productspecification'));
            });
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'));
            }else{
                $objSpecification = Specification::GetById($request['id']);
                $objSpecification->delete();
                return $this->SendSuccessResponse(null,array('result'=>$objSpecification));
            }
        } catch (\Exception $ex) {
            if(config('env.app_debug'))return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
    public function ChangeSpecifications(Request $request){
        if(isset($request['specifications_id']) && $request['specifications_id']!=""){
            //validamos la existencia
            if(Specification::where( (new Specification)->getTable().'.id',"=",$request['specifications_id'])->exists()){
                $objSpecification = Specification::find($request['specifications_id']);
                $objSpecification->is_global_filter = $request['is_global_filter'];
                $objSpecification->save();
                return $this->SendSuccessResponse(null, array($objSpecification));
            }else{
                return $this->SendErrorResponse(null, array('El id ingresado no existe'));
            }
        }else{
            return $this->SendErrorResponse(null, ['El id seleccionado no es valido']);
        }
    }
    //consulta para obtener las especificaciones que tengan el isfilter activado
    public function OrderFilterSpecification(Request $request){
        /* return $this->SendSuccessResponse(null, Specification::GetFilterSpecification($request['categorie'])); */
        return $this->SendSuccessResponse(null, Specification::GetFilterSpecification($request['categorie']));
    }
}
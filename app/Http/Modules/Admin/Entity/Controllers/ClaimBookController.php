<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Models\Database\Parameter;
use App\Http\Models\Database\ClaimBook;
use App\Http\Models\Database\Order;
use App\Http\Modules\Admin\Services\ValidationClaimBook;

class ClaimBookController extends ApiController{

    public function Get(Request $request){
        if (isset($request["claim_book_id"])) {
            return $this->SendSuccessResponse(null,ClaimBook::GetById($request["claim_book_id"]));
        }if (isset($request["customer_dni"])) {
            return $this->SendSuccessResponse(null,ClaimBook::GetByDNI($request["customer_dni"]));
        }else{
            return $this->SendSuccessResponse(null,ClaimBook::all());
        }
    }

    public function Register(Request $request)
    {
        try {
            $msg_validation=null;
            $validator = ValidationClaimBook::ClaimBookRegister($request,$msg_validation);
            if ($validator->fails()) {
                return $this->SendErrorResponse(trans($msg_validation.'form.result.error'),$validator->errors());
            }else{
                $objClaimBook = new ClaimBook();
                $objClaimBook->type = $request['type'];
                $objClaimBook->correlative = $request['correlative'];
                $objClaimBook->customer_name = $request['customer_name'];
                $objClaimBook->customer_dni = $request['customer_dni'];
                $objClaimBook->customer_address = $request['customer_address'];
                $objClaimBook->customer_email = $request['customer_email'];
                $objClaimBook->customer_phone = $request['customer_phone'];
                $objClaimBook->flg_younger = $request['flg_younger'];
                $objClaimBook->parent_name = $request['parent_name'];
                $objClaimBook->parent_dni = $request['parent_dni'];
                $objClaimBook->parent_address = $request['parent_address'];
                $objClaimBook->parent_email = $request['parent_email'];
                $objClaimBook->parent_phone = $request['parent_phone'];
                $objClaimBook->status = $request['status'];
                $objClaimBook->detail_product = $request['detail_product'];
                $objClaimBook->detail = $request['detail'];
                $objClaimBook->detail_answer = $request['detail_answer'];
                $objClaimBook->detail_answer = $request['date_register'];
                $objClaimBook->detail_answer = $request['date_answer'];
                $objClaimBook->save();
                return $this->SendSuccessResponse(trans($msg_validation.'form.result.success'),array('result'=>$objClaimBook));
            }
        } catch (\Exception $ex) {
            if (config('env.app_debug')) return $this->SendErrorResponse($ex->getMessage());
            else return $this->SendErrorResponse();
        }
    }
}
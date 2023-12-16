<?php

namespace App\Http\Modules\Admin\Entity\Controllers;

use App\Http\Common\Controllers\ApiController;
use App\Http\Models\Database\TypeGroup;
use Illuminate\Http\Request;

class TypeGroupController extends ApiController{

    public function Get(Request $request){
        if (isset($request["type_group_id"])) {
            return $this->SendSuccessResponse(null,TypeGroup::GetById($request["type_group_id"]));
        }else{
            return $this->SendSuccessResponse(null,TypeGroup::all());
        }
    }

}
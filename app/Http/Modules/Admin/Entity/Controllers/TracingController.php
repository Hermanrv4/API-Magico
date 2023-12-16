<?php
namespace App\Http\Modules\Admin\Entity\Controllers;
use App\Http\Common\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Models\Database\Tracing;
use App\Http\Models\Database\Parameter;
use Carbon\Carbon;

class TracingController extends ApiController{
    public function Get(Request $request){
        return $this->SendSuccessResponse(null, Tracing::all());
    }
    public function Register(Request $request){
        try{
            $objTracing=new Tracing();
            $objTracing->id_user    = $request["id_user"];
            $objTracing->page_title = $request["page_title"];
            $objTracing->action     = $request["action"];
            $objTracing->object     = $request["object"];
            $objTracing->url_section= $request["section"];
            $objTracing->value      = $request["value"];
            $objTracing->user_valid = $request["user_valid"] == "1" ? 1 : 0;
            $objTracing->sendobj    = $request["sendobj"];
            $objTracing->save();
            return $this->SendSuccessResponse(null, $objTracing);
        }catch(Exception $e){
            return $this->SendErrorResponse(null, $e);
        }
    }
    // secciones mas visitadas en un rango de fechas
    public function GetVisitPage(Request $request){
        if(isset($request["date_start"])){
            if(isset($request["option"]) && $request["option"]=='detail' ){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetDataFilterVisitOfDate($request["date_start"], $request["date_end"])->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetVisitPageOfDate($request["date_start"], $request["date_end"])->get());
            }
        }else{
            if($request["show_list"]=="all"){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetVisitPage()->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetVisitPage()->take($request["show_list"])->get());
            }
        }
    }
    public function GetPreviewProduct(Request $request){
        if(isset($request["date_start"])){
            if(isset($request["option"]) && $request["option"]=='detail'){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetDataFilterPreviewOfDate($request["date_start"], $request["date_end"])->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetPreviewProductCountOfDate($request["date_start"], $request["date_end"])->orderBy('count_preview', 'desc' )->get());
            }
        }else{
            if($request["show_list"]=="all"){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetPreviewProductCount()->orderBy('count_preview', 'desc')->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetPreviewProductCount()->orderBy('count_preview', 'desc')->take($request["show_list"])->get());
            }
        }
    }
    public function GetAddCardProduct(Request $request){
        if(isset($request["date_start"])){
            if(isset($request["option"]) && $request['option']=='detail'){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetDataFilterAddCardOfDate($request["date_start"], $request["date_end"])->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetAddCardProductCountOfDate($request["date_start"], $request["date_end"])->orderBy('count_addcard', 'desc')->get());
            }
        }else{
            if($request["show_list"]=="all"){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetAddCardProductCount()->orderBy('count_addcard', 'desc')->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetAddCardProductCount()->orderBy('count_addcard', 'desc')->take($request["show_list"])->get());
            }
        }
    }
    public function GetCountVisitUserIsNullOfDate(Request $request){
        if(isset($request["date_start"])){
            return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountVisitUserIsNullOfDate($request["date_start"], $request["date_end"])->orderBy('count_vist', 'desc')->get());
        }else{
            if($request["show_list"]=="all"){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountVisitUserIsNull()->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountVisitUserIsNull()->take($request["show_list"])->get());
            }
        }
    }
    public function GetCategoryVisit(Request $request){
        if(isset($request["date_start"])){
            if(isset($request['option']) && $request['option']=='detail'){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetDataFilterVisitCategory($request["date_start"], $request["date_end"])->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCategoryVisitOfDate($request["date_start"], $request["date_end"])->orderBy('count_category', 'desc')->get());
            }
        }else{
            if($request["show_list"]=="all"){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCategoryVisit()->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCategoryVisit()->take($request["show_list"])->get());
            }
        }
    }
    public function GetCategoryVisitUserNull(Request $request){
        if($request["show_list"]=="all"){
            return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCategoryVisitUserNull()->get());
        }else{
            return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCategoryVisitUserNull()->get());
        }
    }
    //consultas por usuario
    //paginas mas visitadas por usuarios
    public function GetVisitPageOfUser(Request $request){
        if(isset($request["date_start"])){
            if(isset($request["option"]) && $request["option"]!=''){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetDataFilterVisit($request["id_user"], $request["date_start"], $request["date_end"])->get() );
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountVisitPageOfUserForDate($request["id_user"], $request["date_start"], $request["date_end"])->get());
            }
        }else{
            if(isset($request["show_list"]) && $request["show_list"]=='all'){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountVisitPageOfUser($request["id_user"])->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountVisitPageOfUser($request["id_user"])->take($request["show_list"])->get());
            }
        }
    }
    public function GetPreviewForUser(Request $request){
        if(isset($request["date_start"])){
            if(isset($request["option"]) && $request["option"]!='' ){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetDataFilterPreview($request["id_user"], $request["date_start"], $request["date_end"])->get() );
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountPreviewOfUserForDate($request["id_user"], $request["date_start"], $request["date_end"])->get());
            }
        }else{
            if(isset($request["show_list"]) && $request["show_list"]=='all'){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountPreviewOfUser($request["id_user"])->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountPreviewOfUser($request["id_user"])->orderBy('count_prod', 'desc')->take($request["show_list"])->get());
            }
        }
    }
    public function GetAddCardForUser(Request $request){
        if(isset($request["date_start"])){
            if(isset($request["option"]) && $request["option"]!=''){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetDataFilterAddCard($request['id_user'], $request['date_start'], $request['date_end'])->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountAddCardOfUserForDate($request["id_user"], $request["date_start"], $request["date_end"])->get());
            }
        }else{
            if(isset($request["show_list"]) && $request["show_list"]=='all'){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountAddCardOfUser($request["id_user"])->orderBy('count_prod', 'desc')->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountAddCardOfUser($request["id_user"])->orderBy('count_prod', 'desc')->take($request["show_list"])->get());
            }
        }
    }
    public function GetCategoryOfUser(Request $request){
        if(isset($request["date_start"])){
            if(isset($request['option']) && $request['option']!=''){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetDataFilterVisitCategories($request["id_user"], $request["date_start"], $request["date_end"])->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCategoryVisitForUserForDate($request["id_user"], $request["date_start"], $request["date_end"])->get());
            }
        }else{
            if(isset($request["show_list"]) && $request["show_list"]=='all'){
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCategoryVisitForUser($request["id_user"])->get());
            }else{
                return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCategoryVisitForUser($request["id_user"])->take($request["show_list"])->get());
            }
        }
    }
    public function GetCountWsp(Request $request){
        return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountWsp($request["date_start"])->get());
    }
    public function GetCountMessenger(Request $request){
        return $this->SendSuccessResponse(null, Tracing::withoutGlobalScopes()->GetCountMessenger($request["date_start"])->get());
    }
}
<?php

namespace App\Http\Models\Database;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Base\BaseModel;
use Exception;

class Cart extends BaseModel
{
    // <editor-fold desc="Attributes" defaultstate="collapsed">
    protected $table = 'carts';
    protected $table_reference = 'cart';
    protected $fillable = ['id','user_id','product_id','quantity','observations','created_at','updated_at'];
    protected $string_json = [];
    protected $hidden = ['created_at', 'updated_at'];
    // </editor-fold>

    // <editor-fold desc="Relationship HasMany" defaultstate="collapsed">
    // </editor-fold>
    // <editor-fold desc="Relationship BelongsTo" defaultstate="collapsed">
    // </editor-fold>

    public static function GetByUserId($user_id){
        return Cart::whereRaw('user_id = ?',[$user_id])->get();
    }
    public static function GetByUserIdAndProductId($user_id,$product_id){
        return Cart::whereRaw('user_id = ? and product_id = ?',[$user_id,$product_id])->first();
    }
    public static function DeleteByUserIdAndProductId($user_id,$product_id){
        return Cart::whereRaw('user_id = ? and product_id = ?',[$user_id,$product_id])->delete();
    }
    public static function DeleteProductForUserOrder($user_id, $token){
        // obtenemos la lista de producto por el id de la order
        try{
            // obtenemos el id de la orden por el token
            $order=Order::GetByToken($token);
            $list_detail=OrderDetail::GetByOrderId($order['id']);
            // 
            $array=array();
            for($item=0; $item<count($list_detail); $item++){
                $array[]=$list_detail[$item]['product_id'];
            }
            // 
            $list=array();
            for($item=0; $item<count($array); $item++){
                $obj=Cart::where((new Cart())->getTable().'.product_id',"=", $array[$item])->where((new Cart)->getTable().'.user_id',"=", $user_id)->get();
                $list[]=$obj[0]['id'];
            }
            for($item=0; $item<count($list); $item++){
                $objCart=Cart::GetById($list[$item]);
                $objCart->delete();
            }
            return $list;
        }catch(Exception $e){
            return $e;
        }
    }
    
    public static function GetCartsByLastUpdate(){
        
        try{
            $obj = DB::table('carts')
            ->select(
                'carts.id as cart_id',
                DB::raw('carts.updated_at as last_cart_date'),
                'users.*')
            ->join('users', 'carts.user_id', '=', 'users.id')
            ->orderBy('id', 'asc')
            ->orderBy('last_cart_date', 'desc')
            ->get();
            
            return $obj;

        }catch(Exception $e){
            return $e;
        }
    }

    public static function GetProductsByUserId($user_id){
        return DB::table('carts')
        ->select('products.*', 'product_prices.online_price','carts.quantity')
        ->join('products', 'carts.product_id', '=', 'products.id')
        ->join('product_prices', 'products.id', '=', 'product_prices.product_id')
        ->where('carts.user_id', '=', $user_id)
        ->get();
    }
}

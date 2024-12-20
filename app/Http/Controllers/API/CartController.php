<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Add_cart;

class CartController extends Controller
{
    public function addtocart(Request $request){

        if(auth('sanctum')->check()){
             
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;

            $productcheck = Product::where('id', $product_id)->first();
            
            if($productcheck){
                if(Add_cart::where('product_id', $product_id)->where('user_id', $user_id)->exists()){

                    return response()->json([

                        'status'=>409,
                        'message'=> $productcheck->name.' Already added to cart',

                    ]);

                }
                else{

                    $cartitem = new Add_cart;
                    $cartitem->user_id = $user_id;
                    $cartitem->product_id = $product_id;
                    $cartitem->product_qty = $product_qty;
                    $cartitem->save();

                    return response()->json([

                        'status'=>201,
                        'message'=>'added to cart',

                    ]);
                }
            

        }
        else{

            return response()->json([

                'status'=>404,
                'message'=>'product not found',
            ]);

        }

        }
        else{

            return response()->json([

                'status'=>401,
                'message'=>'Login to add to cart',

            ]);

        }
    }

    public function viewcart(){

        if(auth('sanctum')->check()){

            $user_id = auth('sanctum')->user()->id;
            $cartitem = Add_cart::where('user_id', $user_id)->get();

            return response()->json([

                'status' => 200,
                'cart' => $cartitem,

            ]);

        }
        else{

            return response()->json([

                'status'=>401,
                'message'=>'Login to view cart data',

            ]);

        }

    }

    public function updatequantity($cart_id, $scope){

        if(auth('sanctum')->check()){

            $user_id = auth('sanctum')->user()->id;
            $cartitem = Add_cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            if($scope == 'inc'){
                $cartitem->product_qty += 1;
            }
            else if($scope == 'dec'){
                $cartitem->product_qty -= 1;
            }

            $cartitem->update();

            return response()->json([

                'status'=>200,
                'message'=>'Quantity updated',

            ]); 


        }
        else{

            return response()->json([

                'status'=>401,
                'message'=>'Login to continue',

            ]); 
        }

    }

    public function deletecartitem($cart_id){

        if(auth('sanctum')->check()){

            $user_id = auth('sanctum')->user()->id;
            $cartitem = Add_cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            if($cartitem){

                $cartitem->delete();
                return response()->json([

                    'status'=>200,
                    'message'=>'Cart item removed successfully',
    
                ]); 

            }
            else{

                return response()->json([

                    'status'=>404,
                    'message'=>'Cart item not found',
    
                ]); 

            }

        }
        else{

            return response()->json([

                'status'=>401,
                'message'=>'Login to continue',

            ]); 

        }

    }
}

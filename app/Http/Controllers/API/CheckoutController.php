<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Add_cart;
use App\Models\Order;

class CheckoutController extends Controller
{
    public function placeorder(Request $request){

        if(auth('sanctum')->check()){

            $validator = Validator::make($request->all(), [

                'firstname' => 'required|max:191',
                'lastname' => 'required|max:191',
                'email' => 'required|max:191',
                'number' => 'required|max:191',
                'city' => 'required|max:191',
                'state' => 'required|max:191',
                
            ]);

            if($validator->fails()){
                return response()->json([
                  'status'=>422,
                  'errors'=>$validator->messages(),
                ]);
            }
            else{

                $user_id = auth('sanctum')->user()->id;

                $order = new Order;

                $order->user_id = $user_id;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->email = $request->email;
                $order->number = $request->number;
                $order->address = $request->address;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zip = $request->zip;

                $order->payment_mode = $request->payment_mode;
                $order->tracking_no = 'trenking.com'.rand(1111,9999);

                $order->save();

                $cart = Add_cart::where('user_id', $user_id)->get();

                $orderitems = [];
                foreach($cart as $item){
                    $orderitems[] = [
                        'product_id' => $item->product_id,
                        'qty' => $item->product_qty,
                        'price' => $item->product->selling_price,
                        'merchant' => $item->product->merchant,
                    ];

                    $item->product->update([
                        'qty'=>$item->product->qty - $item->product_qty
                    ]);

                    $order->orderitem()->createMany($orderitems);
                    Add_cart::destroy($cart);
                }
                return response()->json([
                    'status'=>200,
                    'message'=>'Order placed successfully',
                  ]);

            }

        }
        else{

            return response()->json([

                'status'=>404,
                'message'=>'login to continue...',

            ]); 
        }
    }

    public function validateorder(Request $request){

        if(auth('sanctum')->check()){

            $validator = Validator::make($request->all(), [

                'firstname' => 'required|max:191',
                'lastname' => 'required|max:191',
                'email' => 'required|max:191',
                'number' => 'required|max:191',
                'city' => 'required|max:191',
                'state' => 'required|max:191',
                
            ]);

            if($validator->fails()){
                return response()->json([
                  'status'=>422,
                  'errors'=>$validator->messages(),
                ]);
            }
            else{

                return response()->json([
                    'status'=>200,
                    'message'=>'Detail validated successfully',
                  ]);

            }
        }
        else{

            return response()->json([

                'status'=>404,
                'message'=>'login to continue...',

            ]); 
        }


    }
}

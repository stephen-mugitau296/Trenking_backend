<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class FrontEndController extends Controller
{
    public function category(){

        $category = Category::where('status','0')->get();

        return response()->json([
            'status'=>200,
            'category'=>$category,
        ]);
    }

    public function userCategory($merchant){

        $category = Category::where('merchant', $merchant)->where('status','0')->get();

        return response()->json([
            'status'=>200,
            'category'=>$category,
        ]);
    }

    public function product($slug){
        $category = Category::where('slug', $slug)->where('status', '0')->first();

        if($category){

            $product = Product::where('category_id', $category->id)->where('status', '0')->get();

            if($product){

                return response()->json([
                    'status' => 200,
                    'product_data' =>[
                        'product'=>$product,
                        'category'=>$category,
                    ] ,
                ]);

            }
            else{
                return response()->json([
                    'status' => 400,
                    'message' => 'No product found',
                ]);

            }

        }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'No such category found',
            ]);
        } 
    }

    public function fetch_product($slug, $merchant){
        $category = Category::where('slug', $slug)->where('merchant', $merchant)->where('status', '0')->first();

        if($category){

            $product = Product::where('category_slug', $slug)->where('merchant', $merchant)->where('status', '0')->get();

            if($product){

                return response()->json([
                    'status' => 200,
                    'product_data' =>[
                        'product'=>$product,
                        'category'=>$category,
                    ] ,
                ]);

            }
            else{
                return response()->json([
                    'status' => 400,
                    'message' => 'No product found',
                ]);

            }

        }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'No such category found',
            ]);
        }
    }


    public function userproduct($merchant){

        $category = Category::where('merchant', $merchant)->where('status', '0')->first();

        if($category){

            $product = Product::where('merchant', $merchant)->where('status', '0')->get();

            if($product){

                return response()->json([
                    'status' => 200,
                    'product_data' =>[
                        'product'=>$product,
                        'category'=>$category,
                    ] ,
                ]);

            }
            else{
                return response()->json([
                    'status' => 400,
                    'message' => 'No product found',
                ]);

            }

        }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'No such category found',
            ]);
        }

    }

    public function viewproduct($merchant, $product_merchant, $product_id){

        $category = Category::where('merchant', $product_merchant)->where('status', '0')->first();

        if($category){

            $product = Product::where('merchant', $product_merchant)->where('id', $product_id)->where('status', '0')->first();

            if($product){

                return response()->json([
                    'status' => 200,
                    'product' =>$product,
                     
                ]);

            }
            else{
                return response()->json([
                    'status' => 400,
                    'message' => 'No product found',
                ]);

            }

        }
        else{
            return response()->json([
                'status' => 404,
                'message' => 'No such category found',
            ]);
        }

    }
}

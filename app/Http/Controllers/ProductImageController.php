<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function index($product_id){
        $productImage = ProductImage::where('product_id', $product_id);
        if($productImage){
         return response()->json([
             'status'=>200,
             'productImage'=>$productImage,
         ]);
        } 
        else{
              return response()->json([
                   'status'=>404,
                   'message'=>'No category id found',
         ]);
        }
    }

    public function store(Request $request, $id){
        
        $product = Product::findOrFail($id);
        $request->validate([
            'image.*' => 'required|image|mimes:png,jpg,jpeg,webp'
        ]);
        
        $imageData = [];
        if($files = $request->file('image')){
            foreach($files as $key => $file){
                $extension = $file->getClientOriginalExtension();
                $filename = $key.'-'.time(). '.' .$extension;

                $path = "images/product/";

                $file->move($path, $filename);

                $imageData[] =[
                     'product_id' => $id,
                     'image' => $path.$filename,
                ];
            }
        }

        ProductImage::insert($imageData);

        return response()->json([
            'status'=>200,
            'message'=>'product stored successfully',
          ]);

    }
}

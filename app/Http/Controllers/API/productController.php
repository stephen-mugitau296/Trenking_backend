<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class productController extends Controller
{
    public function index(){
       $product = Product::all();

       return response() ->json([
        'status'=>200,
        'products'=>$product,
       ]);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[

            "category_id"=>"required|max:191",
            "slug"=>"required|max:191",
            "name"=>"required|max:191",
            "meta_title"=>"required|max:191",
            "selling_price"=>"required|max:20",
            "qty"=>"required|max:20",
            "brand"=>"required|max:191",
            //"image"=>"required|array",
            "image"=>"required|file|mimes:jpeg,jpg,png|max:2042",

        ]);

        if($validator->fails()){
            return response()->json([
              'status'=>422,
              'errors'=>$validator->messages(),
            ]);
        }
        else{
             $images = [];
             $product = new Product;
             $username = auth('sanctum')->user()->merchant;
             $product->category_id = $request->input("category_id");
             $product->category_slug = $request->input("category_slug");
             $product->merchant = $username;
             $product->slug = $request->input("slug");
             $product->name = $request->input("name");
             $product->description = $request->input("description");

             $product->meta_title = $request->input("meta_title");
             $product->meta_keywords = $request->input("meta_keywords");
             $product->meta_description = $request->input("meta_description");

             $product->brand = $request->input("brand");
             $product->selling_price = $request->input("selling_price");
             $product->original_price = $request->input("original_price");
             $product->qty = $request->input("qty");

             if($request->hasFile('image')){

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename =time().'.'.$extension;
                $file->move('uploads/product/',$filename);
                $product->image ='uploads/product/'.$filename; 
             }
             
            
            // if($files = $request->file('image')){
                
                //foreach($files as $key => $file){
               // $extension = $file->getClientOriginalExtension();
               // $filename =$key.'-'.time().'.'.$extension;
               // $file->move('uploads/product/',$filename);
               // $product->image ='uploads/product/'.$filename;
                //}
             //}
             $product->featured = $request->input("featured");
             $product->popular = $request->input("popular");
             $product->status = $request->input("status");

             $product->save();

             return response()->json([
                'status'=>200,
                'message'=>'product stored successfully',
              ]);

        }

    }

    public function edit($id){

        $product = Product::find($id);
        
        if($product){
        return response() ->json([
         'status'=>200,
         'products'=>$product,
        ]);
        }
        else{
            return response()->json([
                'status'=>404,
                'message'=>'No product found',
            ]);
        }
    }

    

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[

            "category_id"=>"required|max:191",
            "slug"=>"required|max:191",
            "name"=>"required|max:191",
            "meta_title"=>"required|max:191",
            "selling_price"=>"required|max:20",
            "qty"=>"required|max:20",
            "brand"=>"required|max:191",
            

        ]);

        if($validator->fails()){
            return response()->json([
              'status'=>422,
              'errors'=>$validator->messages(),
            ]);
        }
        else{
             $product = Product::find($id);

             if($product){
             $username = auth('sanctum')->user()->merchant;
             $product->category_id = $request->input("category_id");
             $product->category_slug = $request->input("category_slug");
             $product->merchant = $username;
             $product->slug = $request->input("slug");
             $product->name = $request->input("name");
             $product->description = $request->input("description");

             $product->meta_title = $request->input("meta_title");
             $product->meta_keywords = $request->input("meta_keywords");
             $product->meta_description = $request->input("meta_description");

             $product->brand = $request->input("brand");
             $product->selling_price = $request->input("selling_price");
             $product->original_price = $request->input("original_price");
             $product->qty = $request->input("qty");
            
             if($request->hasFile('image')){
                $path = $product->image;
               if(File::exists($path))
               {
                File::delete($path);
               }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename =time().'.'.$extension;
                $file->move('uploads/product/',$filename);
                $product->image ='uploads/product/'.$filename;
             }
             $product->featured = $request->input("featured");
             $product->popular = $request->input("popular");
             $product->status = $request->input("status");

             $product->update();

             return response()->json([
                'status'=>200,
                'message'=>'product update successfully',
              ]);
             }
             else{

                return response()->json([
                    'status'=>404,
                    'message'=>'product not found',
                  ]);

             }
        }
        
            
            }

            public function destroy($id){

                $product = Product::find($id);
        
                if($product){
                    $product->delete();
                    return response()->json([
                        'status' => 200,
                        'message' => 'product deleted successfully',
                    ]);
                }
                else
                {
                    return response()->json([
                        'status' => 404,
                        'message' => 'No product found',
                    ]);
                }

            }

            public function search($key){

                return Product::where('name', 'Like', "%$key%")->get();

               /* if($product){

                    return response() ->json([

                         'status'=>200,
                        'products'=>$product,
                   ]);
             }
              else{
                     return response()->json([
                            'status'=>404,
                            'message'=>'No product found',
                 ]);
             }*/
            }
            public function usersearch($merchant, $key){

                return Product::where('merchant', $merchant)->where('name', 'Like', "%$key%")->get();

               /* if($product){

                    return response() ->json([

                         'status'=>200,
                        'products'=>$product,
                   ]);
             }
              else{
                     return response()->json([
                            'status'=>404,
                            'message'=>'No product found',
                 ]);
             }*/
            }

            
        }
    


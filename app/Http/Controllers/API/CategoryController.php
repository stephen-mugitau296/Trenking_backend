<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index(){
        $category = Category::all();

        return response()->json([
            'status'=>200,
            'category'=>$category,
        ]);
    }

    public function allcategory(){

        $category = Category::where('status','0')->get();

        return response()->json([
            'status'=>200,
            'category'=>$category,
        ]);

    }

    public function edit($id){

        $category = Category::find($id);
       if($category){
        return response()->json([
            'status'=>200,
            'category'=>$category,
        ]);
       } 
       else{
             return response()->json([
                  'status'=>404,
                  'message'=>'No category id found',
        ]);
       }

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'meta_title'=>'required|max:191',
            'slug'=>'required|max:191',
            'name'=>'required|max:191',
            "image"=>"required|image|mimes:jpeg,jpg,png|max:2042",
        ]);

        if($validator->fails()){
             return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
             ]);
        }
        else{

        $category = new Category;

        $username = auth('sanctum')->user()->merchant;
        $logos = auth('sanctum')->user()->logos;
        $category->merchant = $username;
        $category->meta_title = $request->input('meta_title');
        $category->meta_keywords = $request->input('meta_keywords');
        $category->meta_description = $request->input('meta_description');
        $category->slug = $request->input('slug');
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->logos = $logos;
        if($request->hasFile('image')){

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename =time().'.'.$extension;
            $file->move('upload/category/',$filename);
            $category->image ='upload/category/'.$filename;
         }
        $category->status = $request->input('status')== true? '1':'0';

        $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'Category stored successfully',
        ]); 
    }

    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'meta_title'=>'required|max:191',
            'slug'=>'required|max:191',
            'name'=>'required|max:191',
        ]);

        if($validator->fails()){
             return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
             ]);
        }
        else{

        $category = Category::find($id);
         if($category)
           {
              $username = auth('sanctum')->user()->merchant;
              $logos = auth('sanctum')->user()->logos;
              $category->meta_title = $request->input('meta_title');
              $category->meta_keywords = $request->input('meta_keywords');
              $category->meta_description = $request->input('meta_description');
              $category->slug = $request->input('slug');
              $category->name = $request->input('name');
              $category->description = $request->input('description');
              $category->logos = $logos;
              if($request->hasFile('image')){
                $path = $category->image;
               if(File::exists($path))
               {
                File::delete($path);
               }
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename =time().'.'.$extension;
                $file->move('upload/category/',$filename);
                $category->image ='upload/category/'.$filename;
             }
              $category->status = $request->input('status');
              $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'Category updated successfully',
        ]);
    } else {
        return response()->json([
            'status' => 404,
            'message' => 'No record found with category id',
        ]);
    }
    }    
    }

    public function destroy($id){

        $category = Category::find($id);

        if($category){
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Category deleted successfully',
            ]);
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'No record found with category id',
            ]);
        }

    }
}

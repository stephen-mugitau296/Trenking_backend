<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request){
         
        $validator = Validator::make($request->all(), [
            'name'=>'required|max:191',
            'email'=>'required|email|max:191|unique:users,email',
            'merchant'=>'required|max:191|unique:users',
            "logos"=>"required|image|mimes:jpeg,jpg,png|max:2042",
            'password'=>'required|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'validator_errors'=>$validator->messages(),
            ]);

        }
        else{
            $user = new User;
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->merchant = $request->input('merchant');
                
                if($request->hasFile('logos')){

                    $file = $request->file('logos');
                    $extension = $file->getClientOriginalExtension();
                    $filename =time().'.'.$extension;
                    $file->move('logos/business/',$filename);
                    $user->logos ='logos/business/'.$filename;
                 }
                 $user->password = Hash::make($request->input('password'));

                 $user->save();

                 
            $token = $user->createToken($user->email.'Token')->plainTextToken;
            return response()->json([
                'status'=>200,
                'username'=>$user->merchant,
                'token'=>$token,
                'message'=>'Registered successfully'

            ]);
        }
    }


public function login(Request $request){

    $validator = Validator::make($request->all(), [
        'email'=>'required|max:193',
        'password'=>'required',
    ]);

    if($validator->fails()){
        return response()->json([
            'validator_errors'=>$validator->messages(),
        ]);
    }
    else{
        $user = User::where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'=>401,
                'message'=>'Invarid credentials'
            ]);
        } else{
            if($user->role_as == 1){
                $role='admin';
                $token = $user->createToken($user->email.'_AdminToken',['server:admin'])->plainTextToken; 
            }
            else
            {
             $role='';   
             $token = $user->createToken($user->email.'_Token',[""])->plainTextToken;
            }
            
            return response()->json([
                'status'=>200,
                'username'=>$user->name,
                'token'=>$token,
                'message'=>'Logged in successfully',
                'role'=>$role,
            ]);

        }
    }
}

public function logout(){
    auth()->user()->tokens()->delete();

    return response()->json([
        'status'=>200,
        'message'=>'logged out successfully',
    ]);
}

public function userdetails(){

    $user = User::select(['id', 'merchant', 'logos'])->get();

    return response()->json([
        'status'=>200,
        'user'=>$user,
    ]);
}
public function search($key){

    return User::where('merchant', 'Like', "%$key%")->get();

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

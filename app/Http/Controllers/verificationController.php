<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class verificationController extends Controller
{
    public function verify($user_id, Request $request){
        if(!$request->hasValidSigniture()){
            return response()->json([
                'status'=>401,
                'message'=>'invalid/expired url provided'

            ]);
        }

        $user = User::findOrFail($user_id);

        if(!$user->hasVerifiedEmail()){

            $user->markEmailAsVerified();
        }
        else{
            return response()->json([
                'status'=>400,
                'message'=>'Email alrady verified'

            ]);
        }
    }
}

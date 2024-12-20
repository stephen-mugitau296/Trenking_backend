<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

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

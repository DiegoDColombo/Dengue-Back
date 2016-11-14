<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use App\models\Denunciante;
use Auth;
use Response;
use Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function login(Request $request){

        $credentials = $request->only(['email', 'password']);
        $credentials['active'] = 1;
        if(Auth::once($credentials)){
            $user = Auth::user();
            $jwt = $user->jwtProvider();
            return Response::json(['data' => ['access_token' => $jwt,
                                              'user' => $user]
                                    ], 200);
        }
        else{
            Auth::logout();
            return Response::json('Unauthorized', 404);
        }
    }

}

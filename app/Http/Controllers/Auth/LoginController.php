<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    use AuthenticatesUsers;
    //AuthenticateUsersのバリデーションをLineログインの時にも利用できるよう上書きする
    protected function validateLogin(Request $request)
    {
        Log::debug($request);
        //Lineログイン処理時のバリデート
        if(isset($request["sns_id"])){
            Log::debug('LoginController');
            $request->validate([
                'email' => 'required|string',
                'sns_id' => 'required|string',
            ]);
        
        }
        //通常ログイン処理時のバリデート
        else{
            $request->validate([
                $this->username() => 'required|string',
                'password' => 'required|string',
            ]);
        }
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    protected function authenticated(Request $request, $user)
    {
        return $user;
    }
    
    protected function loggedOut(Request $request)
    {
        // セッションを再生成する
        $request->session()->regenerate();

        return response()->json();
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Schedule;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        
        //Line登録処理時のバリデート
        if(array_key_exists('sns_id', $data)){
            return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'sns_id' => ['required', 'string', 'max:255'],
        ]);;
        }
        //通常登録処理時のバリデート
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:strict,dns', 'max:255', 'unique:users'], //不正なメールアドレスをはじくように変更
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    
    protected function snsAccountCreate(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'sns_id' => $data['sns_id'],
        ]);
    }
    
    protected function restoreLineAccount(Request $request){
        $user = User::onlyTrashed()->where('sns_id',$request->input('sns_id'))->first();
        if($user != null){
            $user->restore();
            Log::debug("復元");
            Log::debug($user);
        }
        return $user;
    }
    
    protected function checkLineAccount(Request $request){
        return User::all()->where('sns_id',$request->input('sns_id'))->first();
    }
    
    protected function registered(Request $request, $user)
    {
        return $user;
    }
}

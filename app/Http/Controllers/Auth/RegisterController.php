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
use Illuminate\Support\Facades\DB;

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
        //Line連携アカウントを登録する場合
        if(array_key_exists('sns_id', $data)){
            //Lineアカウントが既に登録済みの場合
            if(DB::table('users')->where('sns_id', $data['sns_id'])->exists()){
                return User::all()->where('sns_id', $data['sns_id'])->first();
            }
            else{
                return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'sns_id' => $data['sns_id'],
                ]);
            }    
        }
        //通常アカウントを登録する場合
        else{
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
    }
    
    protected function registered(Request $request, $user)
    {
        return $user;
    }
}

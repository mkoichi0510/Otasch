<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\User;

class LineMessageApiController extends Controller
{
    public function getUserMessage()
    {
        //Log::debug($request);
        return response(200);
    }
    
    //新規登録処理
    public function getProfile($accessToken){
        //ユーザープロフィールを取得
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
        curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/profile');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($response, true);
        
        $user = array(
            'name'          => $json['displayName'],
            'email'         => null,
            'password'      => null,
            'sns_id'        => $json['userId'],
        );
       
        return $user;
        
    }
    
    public function getLineUserAccount(Request $request){
        //アクセストークンの有効性を検証
        $postData = $request->input('access_token');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.line.me/oauth2/v2.1/verify?access_token=${postData}");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);
        Log::debug($json);
        
        //accessTokenが有効な場合
        if($request->input('channelID') == $json["client_id"] && $json["expires_in"] > 0){
            Log::debug("プロフィール情報取得");
            $user = $this->getProfile($postData);
        }
        // Log::debug($user);
        return response($user, 200);
    }
    
    public function forceDelete(User $user)
    {
        $user = Auth::user();
        $user->delete();
    }
}

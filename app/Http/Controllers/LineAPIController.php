<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\User;

class LineAPIController extends Controller
{
    public function getAccessToken(Request $request)
    {
        $postData = array(
            'grant_type'    => 'authorization_code',
            'code'          => $request->input('code'),
            'redirect_uri'  => $request->input('callbackURL'),
            //'redirect_uri'  => 'https://16e576e7e8ae4836ad78a778e6db6d16.vfs.cloud9.ap-northeast-1.amazonaws.com/home',
            'client_id'     => $request->input('channelID'),
            //'client_id'     => '1656516916',
            'client_secret' => $request->input('channelSecret'),
            //'client_secret' => '174247308853df99a3a9def0e419bd8c',
        );
        

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/oauth2/v2.1/token');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        // Log::debug($response);
        curl_close($ch);
        $json = json_decode($response, true);
        
        //$accessToken = $json;
        //Log::debug($json['access_token']);
        $this->getUserInfo($json['access_token']);
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

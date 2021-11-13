<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

class LineAPIController extends Controller
{
    public function getLineClientData(){
        $postData = array(
            'callback'  => config('app.line_client_callback'),
            // 'client_id'     => $request->input('channelID'),
            'client_id'     => config('app.line_client_id'),
            // 'client_secret' => $request->input('channelSecret'),
            'client_secret' => config('app.line_client_secret'),
        );
        return $postData;
    }
    
    public function getAccessToken(Request $request)
    {
        Log::debug("データ代入");
        $postData = array(
            'grant_type'    => 'authorization_code',
            'code'          => $request->input('code'),
            // 'redirect_uri'  => $request->input('callbackURL'),
            'redirect_uri'  => config('app.line_client_callback'),
            // 'client_id'     => $request->input('channelID'),
            'client_id'     => config('app.line_client_id'),
            // 'client_secret' => $request->input('channelSecret'),
            'client_secret' => config('app.line_client_secret'),
        );
        
        Log::debug(config('app.line_client_callback'));

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
        $this->getProfile($json['access_token']);
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
            'email'         => 'instance@gmail.com',
            'password'      => $json['userId'],
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
        if(config('app.line_client_id') == $json["client_id"] && $json["expires_in"] > 0){
            Log::debug("プロフィール情報取得");
            $user = $this->getProfile($postData);
            return response($user, 200);
        }
        // Log::debug($user);
        return;
    }
    
    public function forceDelete(User $user)
    {
        $user = Auth::user();
        $user->delete();
    }
    
    //取得したLineアカウントが既に登録済みかどうかを返すメソッド
    public function checkLineAccount(Request $request){
        if(DB::table('users')->where('sns_id', $request->input('sns_id'))->exists()){
            $user = User::all()->where('sns_id', $request->input('sns_id'))->first();
            $user['password'] = $request->input('sns_id');
            return response($user, 200); 
        }
        else{
            return response($request, 201);
        }
    }
}

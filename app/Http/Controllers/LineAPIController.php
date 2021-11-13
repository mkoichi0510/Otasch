<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class LineAPIController extends Controller
{
    //.envファイルに登録されているLineクライアントのデータの取得
    public function getLineClientData(){
        $postData = array(
            'callback'  => config('app.line_client_callback'),
            'client_id'     => config('app.line_client_id'),
            'client_secret' => config('app.line_client_secret'),
        );
        return $postData;
    }
    
    //フロント側で取得したアクセストークンを検証
    public function getLineUserAccount(Request $request){
        //アクセストークンを設定
        $accessToken = $request->input('access_token');
        //パラメータの設定
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.line.me/oauth2/v2.1/verify?access_token=${accessToken}");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //送信
        $response = curl_exec($ch);
        
        curl_close($ch);
        //json型のレスポンスをデコード
        $data = json_decode($response, true);
        
        //レスポンスとして受け取ったclient_idがアプリに登録されてある値と等しいかつ有効期限内の場合
        if(config('app.line_client_id') == $data["client_id"] && $data["expires_in"] > 0){
            //アクセストークンを用いてユーザープロフィールを取得
            $user = $this->getProfile($accessToken);
            //取得したユーザープロフィールを返す
            return response($user, 200);
        }
        
        return;
    }
    
    //Lineプラットフォームからアクセストークンを用いてユーザープロフィールを取得するメソッド
    public function getProfile($accessToken){
        //パラメータの設定
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
        curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/profile');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //送信
        $response = curl_exec($ch);
        curl_close($ch);
        //json型のレスポンスをデコード
        $data = json_decode($response, true);
        //レスポンスを新規登録やログイン処理が行いやすい形に変換
        $user = array(
            'name'          => $data['displayName'],
            'email'         => null,
            'password'      => null,
            'sns_id'        => $data['userId'],
        );
       
        return $user;
    }
    
    //Lineアカウントの物理削除
    public function forceDelete()
    {
        //ログイン中のユーザーデータを取得
        $user = auth()->user();
        //物理削除
        $user->forceDelete();
        
        return;
    }
    
}

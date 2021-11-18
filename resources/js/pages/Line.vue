<template>
 <div>
   <a :href="params.url">
     <el-button type="success" v-loading.fullscreen.lock="loading">Lineアカウントで{{setLineMessage}}</el-button>
   </a>
 </div>
</template>

<script>
import { OK } from '../util'
  export default {
    data() {
      return {
        state : this.createState(), //Lineログインで使うランダム文字列格納用
        loading : false, //画面のローディング表示を管理する変数
        //Lineログインで用いるデータ群
        params : {
          url : null, //ユーザーがLineにログインする画面に遷移するためのURLを格納
          code : null,　//ユーザーがLineにログインした際に返されるcodeの値を格納
          state : null, //ユーザーがLineにログインした際に返されるstateの値を格納
          access_token : null, //取得したアクセストークンを格納
        },
      };
    },
    methods:{
      //Lineアカウントを連携するためのacceseTokenをフロント側で取得
      async getAccessToken(){
        this.loading = true;
        var paramaters = new URLSearchParams();
        //パラメータの設定
        paramaters.append('grant_type', 'authorization_code');
        paramaters.append('code', this.params.code);
        paramaters.append('redirect_uri', this.$store.state.auth.line_client_callback);
        paramaters.append('client_id', this.$store.state.auth.line_client_id);
        paramaters.append('client_secret', this.$store.state.auth.line_client_secret);
        //送信
        const response = await axios.post('https://api.line.me/oauth2/v2.1/token', paramaters);
        //アクセストークンが取得できた場合
        if(response.status === OK) {
          this.params.access_token = response.data.access_token;
          //取得したアクセストークンを用いてサーバー側でユーザープロフィールをLineプラットフォームから取得し、新規登録またはログイン処理を行う
          this.registerLineAccount();
          return false;
        }
        this.loading = false;
       },
       
       //ラインアカウントで登録またはログイン処理をする命令をサーバー側に投げるメソッド
       async registerLineAccount(){
        await this.$store.dispatch('auth/registerLineAccount', this.params);
        //成功時
        if(this.apiStatus){
          console.log(this.loading);
          // ホーム画面に移動する
          this.$router.push('/home');
        }
        this.loading = false;
       },
       
       //Lineクライアントデータをサーバー側で取得し、Lineのログイン画面に遷移するためのURLを設定するメソッド
       async setURL(){
         //envファイル内にあるLineクライアントデータを取得し、vuexで管理する命令をサーバー側に投げる
        await this.$store.dispatch('auth/getLineClientData');
        //Lineクライアントデータ取得成功時
        if(this.apiStatus){
          //URLの設定
          this.params.url = `https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=${this.$store.state.auth.line_client_id}&redirect_uri=${this.$store.state.auth.line_client_callback}&state=${this.state}&scope=profile`;
        }
       },
       //Lineのログイン画面に遷移するURLに必要になるランダムの文字列を生成し、生成した値を返すメソッド
       createState(){
        // 生成する文字列の長さ
        const createLength = 8;

        // 生成する文字列に含める文字セット
        const characters = "abcdefghijklmnopqrstuvwxyz0123456789";
        //文字セットの文字数を格納
        const charactersLength = characters.length;
        //生成した文字列を格納するための変数
        let state = "";
        //ランダム文字列の生成
        for(let i=0; i<createLength; i++){
          state += characters[Math.floor(Math.random()*charactersLength)];
        }
        
        return state;
      },
      
      //ユーザーがLineにログインし、ログイン画面にリダイレクトした際にURLに含まれるcodeとstateのパラメータを変数に格納するメソッド
      setParams(){
        //URLに含まれるパラメータを取得
        var param = (new URL(document.location)).searchParams;
        //取得したパラメータを格納
        this.params.code = param.get('code');
        this.params.state = param.get('state');
      },
      
      //このvueファイルが読み込まれた際に実行するメソッド
      async initilizeData(){
        //Lineのログイン画面に遷移するためのURLを設定
        await this.setURL();
        //URLに含まれるパラメータを変数に格納
        this.setParams();
        //Lineのログインに成功し、リダイレクトした時
        if(this.params.code != null){
          //アクセストークンを取得するメソッドを実行
          this.getAccessToken();
        }
      }
    },
    mounted : function(){
      //このvueファイルを表示するのに必要なデータの取得
      this.initilizeData();
    },
    
    computed: {
      //vuexを用いてサーバーに投げた処理が正常に行われたかの結果を取得
      apiStatus () {
        return this.$store.state.auth.apiStatus;
      },
      //画面に表示するLineボタンのメッセージを設定する
      setLineMessage(){
        if(this.$store.state.auth.formStatus){
          return "ログイン";
        }
        else{
          return "新規登録";
        }
      }
    },
  };
</script>
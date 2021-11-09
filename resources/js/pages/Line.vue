<template>
 <div>
   <a :href="params.url">Lineアカウントで登録またはログイン</a>
 </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
  export default {
    data() {
      return {
        state : this.createState(),
        params : {
          url : null,
          code : null,
          state : null,
          access_token : null,
        },
        responseData : null,
      };
    },
    props:{
      componentType:{
        type:Boolean,
      },
      
    },
    methods:{
      async loginLine(){
        var paramaters = new URLSearchParams();
        paramaters.append('grant_type', 'authorization_code');
        paramaters.append('code', this.params.code);
        paramaters.append('redirect_uri', this.$store.state.auth.line_client_callback);
        paramaters.append('client_id', this.$store.state.auth.line_client_id);
        paramaters.append('client_secret', this.$store.state.auth.line_client_secret);
        console.log(this.$store.state.auth.line_client_callback);
        const response = await axios.post('https://api.line.me/oauth2/v2.1/token', paramaters);
        console.log(response.status);
        console.log(response.data);
        if(response.status === OK) {
          this.params.access_token = response.data.access_token;
          this.registerLineAccount();
          return false
        }
        
       },
       //ラインアカウントで登録
       async registerLineAccount(){
        await this.$store.dispatch('auth/registerLineAccount', this.params);
        
        if(this.apiStatus){
          // トップページに移動する
          this.$router.push('/home');
        }
       },
       //Lineクライアントデータの取得
       async setURL(){
        await this.$store.dispatch('auth/getLineClientData');
        console.log("get");
        if(this.apiStatus){
          //URLの設定
          this.params.url = `https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=${this.$store.state.auth.line_client_id}&redirect_uri=${this.$store.state.auth.line_client_callback}&state=${this.state}&scope=profile`;
        }
       },
       createState(){
        // 生成する文字列の長さ
        const createLength = 8;

        // 生成する文字列に含める文字セット
        const characters = "abcdefghijklmnopqrstuvwxyz0123456789";

        const charactersLength = characters.length;
        let state = "";
        for(let i=0; i<createLength; i++){
          state += characters[Math.floor(Math.random()*charactersLength)];
        }
        return state;
      },
      setParams(){
        var param = (new URL(document.location)).searchParams;
        this.params.code = param.get('code');
        this.params.state = param.get('state');
      },
      
      async initilizeData(){
        await this.setURL();
        this.setParams();
        if(this.params.code != null){
          console.log("認証");
          this.loginLine();
        }
      }
    },
    mounted : function(){
      if(this.componentType){
        this.lavel = "LINEアカウントでログイン"
      }
      else{
        this.lavel = "LINEアカウントで登録"
      }
      this.initilizeData();
      
    },
    computed: {
      apiStatus () {
        console.log("comp");
        return this.$store.state.auth.apiStatus;
        
      },
    },
  };
</script>
<template>
  <div v-loading.fullscreen.lock="loading">
    <br>
    <check 
      :checkFormVisible='checkFormVisible'
      :message='checkMessage'
      @confirm="forceDeleteLineAccount" 
      @check-form-close="checkFormVisible=false"
    ></check>
    <accountForm
    :accountFormVisible='accountFormVisible'
    :updateUser='updateUserData'
    @account-form-close="accountFormVisible=false"
    ></accountForm>
    <el-card class="box-card">
        <div slot="header" class="clearfix">
          <h1>アカウント</h1>
        </div>
        <div class="userInfo">
          <el-button type="primary"@click="accountFormVisible=true"　style="float: right">アカウント情報の変更</el-button>
          <br>
          <h2>ユーザー名：{{username}}</h2>
          <h2>パスワード：********</h2>
          <h2>メールアドレス：{{mail}}</h2>
          <div>
            <h2>
              <span>LINEアカウントの連携状態：{{checkLineLogin}}</span>
              <el-button type="danger" v-if="isLineLogin" @click="openCheckForm" style="float: right">LINEアカウントの連携解除</el-button>
            </h2>
          </div>
          <br>
        </div>
      </el-card>
    <br>
  </div>
</template>

<script>
import accountForm from './AccountForm.vue'
import check from './Check.vue'
export default {
  data () {
    return {
      accountFormVisible:false,
      checkFormVisible:false,
      checkMessage:"",
      loading : false, //画面のローディング表示を管理する変数
    }
  },
  components:{
    accountForm,
    check,
  },
  methods:{
    //ユーザー情報の更新
    async updateUserData(data){
      this.loading = true;//ローディング表示を入れる
      //サーバー側に更新処理を投げる
      await this.$store.dispatch('auth/changeUserName', data);
        
      if (this.apiStatus) {
        // エラーメッセージを消してポップアップを閉じる
        this.accountFormVisible=false;
        this.clearErrorMessage();
      }
      this.loading = false;//ローディング表示を消す
    },
    //エラーメッセージをリセットする
    clearErrorMessage(){
      this.$store.commit('auth/setUpdateErrorMessages', null);
    },
    //LINEアカウントのデータを物理削除する命令をサーバー側に投げる
    async forceDeleteLineAccount(){
      this.loading = true;//ローディング表示を入れる
      await this.$store.dispatch('auth/deleteLineAccount');
      if(this.apiStatus){
        this.loading = false;//ローディング表示を消す
        this.$router.push('/login');
      }
    },
    //Lineアカウントの連携解除ボタンを押した際に表示するメッセージを設定し、確認画面ダイアログを開くメソッド
    openCheckForm(){
      this.checkMessage = "すべてのデータが削除されますが本当にLineアカウントの連携を削除してもよろしいですか。";
      this.checkFormVisible = true;
    },
  },
  
  computed: {
    //ログインユーザーのユーザー名を返す
    username () {
      return this.$store.getters['auth/username'];
    },
    //ログインユーザーの登録メールアドレスを返す
    mail () {
      if(this.$store.getters['auth/mail']){
        return this.$store.getters['auth/mail'];
      }
      else{
        return "未設定";
      }
    },
    //LINEアカウントでログイン（連携）の状態を表す文字列を返す
    checkLineLogin() {
      if(this.$store.getters['auth/checkLineLogin'] == null){
        return "未連携";
      }
      else{
        return "連携済み";
      }
    },
    //サーバー側に投げた処理が成功したかどうかを返す
    apiStatus () {
      return this.$store.state.auth.apiStatus;
    },
    //LINEアカウントでログイン（連携）しているかを返す
    isLineLogin(){
      return this.$store.getters['auth/checkLineLogin'];
    },
    
  },
  created(){
    //現在のページを表す値の設定
    this.$store.commit('data/setPageIndex', "5");
  },
  
}
</script>
<template>
  <div>
    <br>
    <check 
      :checkFormVisible='checkFormVisible'
      :message='checkMessage'
      @confirm="forceDelete" 
      @check-form-close="checkFormVisible=false"
    ></check>
    <accountForm
    :accountFormVisible='accountFormVisible'
    :updateUser='updateUser'
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
  components:{
    accountForm,
    check,
  },
  data () {
    return {
      accountFormVisible:false,
      checkFormVisible:false,
      checkMessage:"",
    }
  },
  methods:{
    //ユーザー情報の変更
    async updateUser(data){
      await this.$store.dispatch('auth/changeUserName', data);
        
        if (this.apiStatus) {
          // エラーメッセージを消してポップアップを閉じる
          this.accountFormVisible=false;
          this.clearError();
        }
      },
      clearError(){
        this.$store.commit('auth/setUpdateErrorMessages', null);
    },
    resetPassword(){
      this.$http.get('/password/reset').error(function (data, status, request) {
                // handle error
            })
    },
    async forceDelete(){
      await this.$store.dispatch('auth/deleteLineAccount');
      if(this.apiStatus){
        this.$router.push('/login');
      }
    },
    openCheckForm(){
      console.log("check");
      this.checkMessage = "すべてのデータが削除されますが本当にLineアカウントの連携を削除してもよろしいですか。"
      this.checkFormVisible = true;
    },
  },
  
  computed: {
    username () {
      return this.$store.getters['auth/username'];
    },
    mail () {
      if(this.$store.getters['auth/mail']){
        return this.$store.getters['auth/mail'];
      }
      else{
        return "未設定";
      }
    },
    checkLineLogin() {
      if(this.$store.getters['auth/checkLineLogin'] == null){
        return "未連携";
      }
      else{
        return "連携済み";
      }
    },
    apiStatus () {
      return this.$store.state.auth.apiStatus
    },
    isLineLogin(){
      return this.$store.getters['auth/checkLineLogin'];
    },
    
  }
  
}
</script>
<style>
  .body{
    padding:0; 
    margin:0;
    height: 100%;
  }
</style>
<template>
  <div class="container--small">
    <div class="panel">
        <el-form ref="form" :model="registerForm" label-width="150px" @submit.prevent="register">
          <el-form-item label="アカウント名">
            <el-input v-model="registerForm.name"></el-input>
          </el-form-item>
          <div v-if="registerErrors">
            <ul v-if="registerErrors.name">
              <li :style="errors" v-for="msg in registerErrors.name" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="メールアドレス">
            <el-input v-model="registerForm.email"></el-input>
          </el-form-item>
          <div v-if="registerErrors" >
            <ul v-if="registerErrors.email">
              <li :style="errors" v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="パスワード">
            <el-input type="password" v-model="registerForm.password"></el-input>
          </el-form-item>
          <div v-if="registerErrors" >
            <ul v-if="registerErrors.password">
              <li :style="errors" v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="確認用パスワード">
            <el-input type="password" v-model="registerForm.password_confirmation"></el-input>
          </el-form-item>
            <div class="form__button">
              <el-row>
                <el-button type="primary"@click="register">新規登録</el-button>
              </el-row>
            </div>
        </el-form>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      //ユーザーがForm画面で入力するデータを格納する変数
      registerForm: {
        name: '',//ユーザー名
        email: '',//ユーザーメールアドレス
        password: '',//ユーザーパスワード
        password_confirmation: '' //パスワードの確認用
      },
      //エラーメッセージの色
      errors: {
          color: "red",
      },
    }
  },
  methods:{
    //ユーザー登録を行う処理をサーバー側に投げるメソッド
    async register () {
      // authストアのresigterアクションを呼び出す
      await this.$store.dispatch('auth/register', this.registerForm);
      
      if(this.apiStatus){
        // トップページに移動する
        this.$router.push('/home');
      }
    },
    //エラーメッセージのリセット
    clearError(){
      this.$store.commit('auth/setRegisterErrorMessages', null);
    }
  },
  computed: {
    //サーバー側に投げた処理が成功したかどうかを返す
    apiStatus () {
      return this.$store.state.auth.apiStatus;
    },
    //入力データに不備があったときにエラーメッセージをauthストアから取得するメソッド
    registerErrors(){
      return this.$store.state.auth.registerErrorMessages;
    }
  },
}
</script>

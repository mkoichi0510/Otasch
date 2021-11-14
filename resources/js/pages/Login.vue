<template>
  <div class="container--small">
    <div class="panel">
      <el-form ref="form" :model="loginForm" label-width="120px" @submit.prevent="login">
          <el-form-item label="Email">
            <el-input v-model="loginForm.email"></el-input>
          </el-form-item>
          <div :style="errors"  v-if="loginErrors" >
            <ul v-if="loginErrors.email">
              <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="Password">
            <el-input type="password"　v-model="loginForm.password"></el-input>
          </el-form-item>
          <div :style="errors" v-if="loginErrors">
            <ul v-if="loginErrors.password">
              <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
            </ul>
          </div>
            <div class="form__button">
              <el-row>
                <el-button type="primary"@click="login">Login</el-button>
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
      loginForm: {
        email: '',
        password: ''
      },
      errors: {
          color: "red",
      },
    }
  },
  methods:{
    async login(){
      // authストアのloginアクションを呼び出す
      await this.$store.dispatch('auth/login', this.loginForm);
        
      if (this.apiStatus) {
        // トップページに移動する
        this.$router.push('/home');
      }
    },
    //エラーメッセージのリセット
    clearErrorMessage(){
      this.$store.commit('auth/setLoginErrorMessages', null);
    },
  },
  created(){
    this.clearErrorMessage();
  },
  computed: {
    //サーバー側に投げた処理が成功したかどうかを返す
    apiStatus () {
      return this.$store.state.auth.apiStatus;
    },
    //エラーメッセージの取得
    loginErrors () {
      return this.$store.state.auth.loginErrorMessages;
    }
  },
}
</script>
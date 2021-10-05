<template>
  <div class="container--small">
    <div class="panel">
        <el-form ref="form" :model="registerForm" label-width="120px" @submit.prevent="register">
          <el-form-item label="Name">
            <el-input v-model="registerForm.name"></el-input>
          </el-form-item>
          <div v-if="registerErrors">
            <ul v-if="registerErrors.name">
              <li :style="errors" v-for="msg in registerErrors.name" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="Email">
            <el-input v-model="registerForm.email"></el-input>
          </el-form-item>
          <div v-if="registerErrors" >
            <ul v-if="registerErrors.email">
              <li :style="errors" v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="Password">
            <el-input type="password" v-model="registerForm.password"></el-input>
          </el-form-item>
          <div v-if="registerErrors" >
            <ul v-if="registerErrors.password">
              <li :style="errors" v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="Password (confirm)">
            <el-input type="password" v-model="registerForm.password_confirmation"></el-input>
          </el-form-item>
            <div class="form__button">
              <el-row>
                <el-button type="primary"@click="register">Register</el-button>
              </el-row>
              <!--<button type="submit" class="button button--inverse">register</button>-->
            </div>
        </el-form>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      registerForm: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      errors: {
          color: "red",
        },
    }
  },
  methods:{
      async register () {
        // authストアのresigterアクションを呼び出す
        console.log("登録");
        await this.$store.dispatch('auth/register', this.registerForm)
        if(this.apiStatus){
          // トップページに移動する
          this.$router.push('/home')
        }
    },
    clearError(){
      this.$store.commit('auth/setRegisterErrorMessages', null)
    }
  },
  computed: {
    apiStatus () {
      return this.$store.state.auth.apiStatus
    },
    registerErrors(){
      return this.$store.state.auth.registerErrorMessages
    }
  },
}
</script>

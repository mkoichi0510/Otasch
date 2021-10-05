<script>
export default {
  data () {
    return {
        footerStyle: {
          width: "100%",
          backgroundColor: "#89c7de",
          color: "#fff",
          textAlign: "center",
          padding: "30px 0",

          position: "absolute",/*←絶対位置*/
          bottom: 0, /*下に固定*/
        },
    }
  },
  methods: {
    async logout () {
      await this.$store.dispatch('auth/logout')
      if(this.apiStatus){
        this.$router.push('/login')
      }
    }
  },
  computed:{
    isLogin(){
      return this.$store.getters['auth/check']
    },
    apiStatus () {
      return this.$store.state.auth.apiStatus
    },
  },
}
</script>
<template>
  <el-footer  class="footer">
    <div class="button_wrapper">
      <button v-if="isLogin" class="button button--link" @click="logout">Logout</button>
      <RouterLink v-else class="button button--link" to="/login">
        Login / Register
      </RouterLink>
    </div>
  </el-footer>
</template>

<style>
  .el-footer{
    background-color: #31a9ee; /* フッターの背景色を指定する */
    color: #FFFFFF; /* フッターのフォントの色を指定する */
    
  }
  .button_wrapper{
     text-align:center;
     padding: 20px;
  }
</style>
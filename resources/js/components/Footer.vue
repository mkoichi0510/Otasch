<template>
  <el-footer  class="footer">
    <div class="button_wrapper">
      <span>
        <div style="float: right">©2021 町田晃一</div>
        <div class="logined"  v-if="isLogin">
          <button  @click="logout">ログアウト</button>
        </div>
        <div v-else class="unLogin" >
          <RouterLink to="/login">
            ログイン/新規登録
          </RouterLink>
        </div>
      </span>
    </div>
  </el-footer>
</template>

<script>
export default {
  methods: {
    //ログアウトする処理をサーバー側に投げる
    async logout () {
      await this.$store.dispatch('auth/logout');
      if(this.apiStatus){
        this.$router.push('/login');
      }
    },
  },
  computed:{
    //ログインしているかのチェック
    isLogin(){
      return this.$store.getters['auth/check'];
    },
    //サーバー側に投げた処理が完了したかの判定を返す
    apiStatus () {
      return this.$store.state.auth.apiStatus;
    },
  },
}
</script>

<style>
  .el-footer{
    background-color: #545c64; /* フッターの背景色を指定する */
    color: #FFFFFF; /* フッターのフォントの色を指定する */
  }
  .button_wrapper{
     text-align:center;
     padding: 20px;
  }
</style>
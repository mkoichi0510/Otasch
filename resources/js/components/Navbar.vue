<template>
  <nav class="navbar" v-if="isLogin">
    <el-menu
      class="el-menu-demo"
      mode="horizontal"
      background-color="#545c64"
      text-color="#fff"
      active-text-color="#ffd04b"
    >
    <el-menu-item @click="MoveHome">
      ホーム
    </el-menu-item>
    <el-menu-item @click="MovePreview">
      予定一覧
    </el-menu-item>
    <el-menu-item @click="MoveLog">
      記録
    </el-menu-item>
    <el-menu-item @click="MoveAccount">
      アカウント
    </el-menu-item>
    </el-menu>
  </nav>
</template>

<script>
export default {
  methods:{
    //ホーム画面に移動
    MoveHome(){
      this.$router.push("/home").catch(() => {});
    },
    //予定一覧画面に移動
    MovePreview(){
      this.$router.push("/preview").catch(() => {});
    },
    //記録画面に移動
    MoveLog(){
      this.$router.push("/log").catch(() => {});
    },
    //アカウント画面に移動
    MoveAccount(){
      this.$router.push("/account").catch(() => {});
    },
  },
  computed: {
    //ユーザーがログインしているかの判定
    isLogin () {
      return this.$store.getters['auth/check'];
    },
  }
}
</script>

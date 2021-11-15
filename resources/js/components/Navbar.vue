<template>
  <nav class="navbar">
    <el-menu
      class="el-menu-demo"
      mode="horizontal"
      background-color="#545c64"
      text-color="#fff"
      active-text-color="#ffd04b"
      :default-active="getIndex"
    >
    <el-menu-item index="1" @click="MoveTop">
      Otasche
    </el-menu-item>
    <el-menu-item index="2" @click="MoveHome" v-if="isLogin">
      ホーム
    </el-menu-item>
    <el-menu-item index="3" @click="MovePreview" v-if="isLogin">
      予定一覧
    </el-menu-item>
    <el-menu-item index="4" @click="MoveLog" v-if="isLogin">
      記録
    </el-menu-item>
    <el-menu-item index="5" @click="MoveAccount" v-if="isLogin">
      アカウント
    </el-menu-item>
    </el-menu>
  </nav>
</template>

<script>
export default {
  methods:{
    //トップ画面に移動
    MoveTop(){
      this.$router.push("/").catch(() => {});
    },
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
    //現在のページのインデックス番号を取得
    getIndex(){
      return this.$store.state.data.pageIndex;
    }
  }
}
</script>
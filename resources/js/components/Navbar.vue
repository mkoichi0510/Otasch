<script>
export default {
  data () {
    return {
      //isLogin:'',
      activeindex: '0'
    }
  },
  methods:{
    getUser(){
        axios.get('/user').then((response)=>{
          console.log(response);
        this.isLogin = response.data.name;
      })
    },
    MoveHome(){
      this.$router.push("/home").catch(() => {});
    },
    MovePreview(){
      this.$router.push("/preview").catch(() => {});
    },
    MoveAccount(){
      this.$router.push("/account").catch(() => {});
    },
    MoveLog(){
      this.$router.push("/log").catch(() => {});
    }
  },
  mounted(){
    //this.getUser();
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    username () {
      return this.$store.getters['auth/username']
    }
  }
}
</script>
<template>
  <nav class="navbar" v-if="isLogin">
    <el-menu
      default-active="activeIndex"
      class="el-menu-demo"
      mode="horizontal"
      background-color="#545c64"
      text-color="#fff"
      active-text-color="#ffd04b"
      v-if="isLogin"
    >
      <el-menu-item activeIndex="1" @click="MoveHome">
      ホーム
    </el-menu-item>
    <el-menu-item activeIndex="2" @click="MovePreview">
      予定一覧
    </el-menu-item>
    <el-menu-item activeIndex="3" @click="MoveLog">
      記録
    </el-menu-item>
    <el-menu-item activeIndex="4" @click="MoveAccount">
      アカウント
    </el-menu-item>
    </el-menu>
  </nav>
</template>


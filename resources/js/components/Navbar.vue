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
      this.$router.push("/home");
       //axios.get('/home');
    },
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
  <nav class="navbar">
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
      Home
    </el-menu-item>
    <!--<el-menu-item activeIndex="2" @click="MoveForm">-->
    <!--      Login / Register-->
    <!--</el-menu-item>-->
    </el-menu>
  </nav>
</template>


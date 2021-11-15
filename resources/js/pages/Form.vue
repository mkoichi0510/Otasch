<template>
 <div>
  <h1>Form</h1>
      <el-tabs v-model="activeName">
        <el-tab-pane label="Login" name="first"><login></login></el-tab-pane>
        <el-tab-pane label="Register" name="second"><register></register></el-tab-pane>
      </el-tabs>
      <lineApi></lineApi>
 </div>
</template>

<script>
import login from './Login.vue';
import register from './Register.vue';
import lineApi from './Line.vue';
  export default {
    components: {
      login,
      register,
      lineApi,
    },
    data() {
      return {
        activeName: 'first'
      };
    },
    created(){
      //現在のページを表す値の設定
      this.$store.commit('data/setPageIndex', "1");
  },
  };
</script>
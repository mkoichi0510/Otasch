<template>
 <div>
  <h1>ログイン・新規登録</h1>
      <el-tabs v-model="activeName">
        <el-tab-pane label="ログイン" name="first"><login></login></el-tab-pane>
        <el-tab-pane label="新規登録" name="second"><register></register></el-tab-pane>
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
    watch:{
      //画面にログインフォームが表示されるときはtrue,新規登録フォームが表示されるときはfalseを設定する
      activeName(newVal){
        if(newVal=="first"){
          this.$store.commit('auth/setFormStatus', true);
        }
        else if(newVal=="second"){
          this.$store.commit('auth/setFormStatus', false);
        }
      }
    }
  };
</script>
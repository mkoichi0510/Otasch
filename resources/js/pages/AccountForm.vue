<template>
  <div>
    <br>
    <el-dialog 
        title="新たに設定するアカウント情報" 
        :visible.sync="accountFormVisible" 
        width="70%"
        :before-close="handleClose"
        >
      <el-form ref="form" label-width="120px">
          <el-form-item label="ユーザー名">
            <el-input v-model="updateForm.name"></el-input>
          </el-form-item>
          <div v-if="updateErrors" class="errors">
            <ul v-if="updateErrors.name">
              <li  v-for="msg in updateErrors.name" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="メールアドレス">
            <el-input v-model="updateForm.email"></el-input>
          </el-form-item>
           <div v-if="updateErrors" class="errors">
            <ul v-if="updateErrors.email">
              <li  v-for="msg in updateErrors.email" :key="msg">{{ msg }}</li>
            </ul>
          </div>
            <div class="form__button">
              <el-row>
                <el-button type="primary"@click="updateUser(updateForm)">変更</el-button>
              </el-row>
            </div>
        </el-form>
    </el-dialog>
    <br>
  </div>
</template>

<script>
export default {
  data () {
    return {
       updateForm:{
         name:this.$store.getters['auth/username'],
         email:this.$store.getters['auth/mail'],
       },
    }
  },
  props:{
      accountFormVisible:{
          type:Boolean,
          default:false,
      },
      updateUser:{
        type:Function,
      }
  },
  methods:{
    handleClose() {
        this.$emit('account-form-close');
        this.clearError();
        this.updateForm.name = this.$store.getters['auth/username'];
        this.updateForm.email = this.$store.getters['auth/mail'];
        return;
    },
    clearError(){
        this.$store.commit('auth/setUpdateErrorMessages', null);
    },
  },
  computed: {
    //エラーメッセージの取得
    updateErrors () {
      return this.$store.state.auth.updateErrorMessages;
    },
  }
  
}
</script>
<style>
  .errors{
   color: red;
  }
</style>
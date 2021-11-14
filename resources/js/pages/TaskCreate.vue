<template>
  <div class="container--small">
      <el-dialog 
        title="新たに追加するタスク" 
        :visible.sync="createFormVisible" 
        width="70%"
        :before-close="handleClose"
        >
      <el-form ref="form" label-width="120px">
          <el-form-item label="タスク名">
            <el-input v-model="createForm.name"></el-input>
          </el-form-item>
          <div v-if="createTaskErrors">
            <ul v-if="createTaskErrors.name">
              <li :style="errors" v-for="msg in createTaskErrors.name" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="優先度">
            <el-input-number v-model="createForm.priority":min="1" :max="10"></el-input-number>
          </el-form-item>
          <el-form-item label="タスクの詳細">
            <el-input type="textarea" v-model="createForm.Text"></el-input>
          </el-form-item>
          <div v-if="createTaskErrors">
            <ul v-if="createTaskErrors.Text">
              <li :style="errors" v-for="msg in createTaskErrors.Text" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
            <div class="form__button">
              <el-row>
                <el-button type="primary"@click="registerTask">タスクの新規作成</el-button>
              </el-row>
            </div>
        </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data () {
    return {
      //タスクの新規作成時にユーザーからの入力値を格納する変数
      createForm: {
        name: '', //タスク名
        Text: '', //タスクの詳細
        priority: 1, //タスクの優先度
        schedule_id: null, //タスクの結び付いているスケジュールのid
      },
      //エラーメッセージの色
      errors: {
        color: "red",
      },
    }
  },
  props:{
    //タスク新規作成ダイアログの表示非表示を管理する変数
    createFormVisible:{
        type:Boolean,
        default:false,
    },
  },
  methods:{
    //ダイアログを閉じる処理を親コンポーネントに投げる
    handleClose() {
        this.$emit('create-form-close');
        return;
    },
    //ダイアログに入力した値でタスクの新規作成処理を行う命令を親コンポーネントに投げる
    registerTask(){
        this.$emit('register-task',this.createForm);
    },
    //ダイアログのフォームを初期化するメソッド
    initialzleCreateForm(){
      this.createForm.name = '';
      this.createForm.Text = '';
      this.createForm.priority = 1;
    },
    //エラーメッセージをリセット
    clearError(){
      this.$store.commit('data/setCreateTaskErrorMessages', null);
    },
  },
  computed: {
    //エラーメッセージをdataストアから取得するメソッド
    createTaskErrors () {
      return this.$store.state.data.createTaskErrorMessages;
    }
  },
  watch:{
    //タスク新規作成ダイアログが非表示から表示に切り替わった際に実行
    createFormVisible(newValue){
      if(newValue){
        this.clearError();
        this.initialzleCreateForm();
      }
    }
  }
}
</script>


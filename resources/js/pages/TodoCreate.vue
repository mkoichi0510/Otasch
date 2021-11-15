<template>
  <div class="container--small">
      <el-dialog 
        title="新たに追加する予定" 
        :visible.sync="createFormVisible" 
        width="70%"
        :before-close="handleClose"
        >
      <el-form ref="form" label-width="120px">
          <el-form-item label="予定名">
            <el-input v-model="createForm.name"></el-input>
          </el-form-item>
          <div v-if="createScheduleErrors">
            <ul v-if="createScheduleErrors.name">
              <li :style="errors" v-for="msg in createScheduleErrors.name" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="優先度">
            <el-input-number v-model="createForm.priority":min="1" :max="10"></el-input-number>
          </el-form-item>
          <el-form-item label ="期限">
              <el-date-picker
                v-model="createForm.term" 
                type="date" 
                placeholder="期限を選択してください" 
                format="yyyy/MM/dd"
                value-format="yyyy-MM-dd"
              ></el-date-picker>
            </el-form-item>
          <div v-if="createScheduleErrors">
            <ul v-if="createScheduleErrors.term">
              <li :style="errors" v-for="msg in createScheduleErrors.term" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="予定の詳細">
            <el-input type="textarea" v-model="createForm.Text"></el-input>
          </el-form-item>
          <div v-if="createScheduleErrors">
            <ul v-if="createScheduleErrors.Text">
              <li :style="errors" v-for="msg in createScheduleErrors.Text" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
            <div class="form__button">
              <el-row>
                <el-button type="primary"@click="registerSchedule">作成</el-button>
              </el-row>
            </div>
        </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name:'createForm',
  data () {
    return {
      //予定の新規作成時にユーザーからの入力値を格納する変数
      createForm: {
        name: '',//予定名
        Text: '',//予定の詳細
        priority: 1,//予定の優先度
        term: null,//予定の期限
      },
      //エラーメッセージの色
      errors: {
          color: "red",
        },
    }
  },
  props:{
    //予定新規作成ダイアログの表示非表示を管理する変数
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
    //ダイアログに入力した値で予定の新規作成処理を行う命令を親コンポーネントに投げる
    registerSchedule(){
        this.$emit('register-schedule',this.createForm);
    },
    //ダイアログのフォームを初期化するメソッド
    initialzleRegisterForm(){
      this.createForm.name = '';
      this.createForm.Text = '';
      this.createForm.priority = 1;
      this.createForm.term = null;
    },
    //エラーメッセージをリセット
    clearError(){
      this.$store.commit('data/setCreateScheduleErrorMessages', null);
    },
  },
  computed: {
    //エラーメッセージをdataストアから取得するメソッド
    createScheduleErrors () {
      return this.$store.state.data.createScheduleErrorMessages;
    }
  },
  watch:{
    //タスク新規作成ダイアログが非表示から表示に切り替わった際に実行
    createFormVisible(newvalue){
      if(newvalue){
        this.clearError();
      　this.initialzleRegisterForm();
      }
    }
  }
}
</script>


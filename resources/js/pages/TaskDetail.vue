<template>
  <div class="container--small">
      <check 
        :checkFormVisible='checkFormVisible'
        :message='checkMessage'
        @confirm="forceDelete" 
        @check-form-close="checkFormVisible=false"
      ></check>
      <el-dialog 
        title="詳細" 
        :visible.sync="detailFormVisible" 
        width="70%"
        :before-close="handleClose"
        >
      <el-row>
        <el-button v-if="detailMode && checkTaskState && checkScheduleState" type="primary"@click="detailMode=false"　style="float: right">編集</el-button>
      </el-row>
      <br>
      <el-form ref="form" label-width="120px">
          <el-form-item label="タスク名">
            <el-input　 v-model="editForm.name" :disabled="detailMode"></el-input>
          </el-form-item>
          <div v-if="updateTaskErrors">
            <ul v-if="updateTaskErrors.name">
              <li :style="errors" v-for="msg in updateTaskErrors.name" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="優先度">
            <el-input-number v-model="editForm.priority":min="1" :max="10" :disabled="detailMode" ></el-input-number>
          </el-form-item>
          <div v-if="updateTaskErrors">
            <ul v-if="updateTaskErrors.term">
              <li :style="errors" v-for="msg in updateTaskErrors.term" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label ="詳細">
              <el-input　type="textarea" v-model="editForm.Text" :disabled="detailMode"></el-input>
            </el-form-item>
            <div v-if="updateTaskErrors">
            <ul v-if="updateTaskErrors.Text">
              <li :style="errors" v-for="msg in updateTaskErrors.Text" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
            <div class="form__button">
              <el-row>
                <el-button v-if="detailMode && checkTaskState && checkScheduleState" type="primary"@click="deleteTask">タスクの完了</el-button>
                <el-button v-if="detailMode && checkScheduleState" type="danger" @click="openCheckForm" style="float: right">タスクの取り消し</el-button>
                <el-button v-if="!detailMode" type="primary"@click="updateTask">更新</el-button>
              </el-row>
            </div>
        </el-form>
    </el-dialog>
  </div>
</template>

<script>
import check from './Check.vue'
export default {
  name:'detailFormTask',
  components:{
    check,
  },
  data(){
    return{
      editForm: this.detailData,//タスクの詳細画面で表示する予定データを格納する変数
      detailMode:true, //タスクの詳細ダイアログの表示非表示を管理する変数
      checkFormVisible:false, //確認画面ダイアログの表示非表示を管理する変数
      checkMessage:"", //確認画面ダイアログで表示するメッセージを格納する変数
      //エラーメッセージの色
      errors: {
        color: "red",
      },
    }
  },

  props:{
    //タスク詳細ダイアログの表示非表示を管理する変数
    detailFormVisible:{
        type:Boolean,
        default:false,
    },
    //選択した予定データ
    detailData:{
      type:Object,
      default:null,
    },
  },
  methods:{
    //タスク詳細ダイアログを閉じる処理をする命令を親コンポーネントに投げるメソッド
    handleClose() {
      this.$emit('detail-form-close');
      return;
    },
    //タスクの達成処理をする命令を親コンポーネントに投げるメソッド
    deleteTask(){
      this.$emit('delete-task',this.editForm);
    },
    //タスクの更新処理をする命令を親コンポーネントに投げるメソッド
    updateTask(){
      this.$emit('update-task',this.editForm);
    },
    //タスクの物理削除をする命令を親コンポーネントに投げるメソッド
    forceDelete(){
      this.$emit('force-delete', this.editForm);
    },
    //確認ダイアログに表示するメッセージを設定し、確認ダイアログを表示するメソッド
    openCheckForm(){
      this.checkMessage = "本当にこのタスクを削除してもよろしいですか。"
      this.checkFormVisible = true;
    },
    //エラーメッセージのリセット
    clearError(){
      this.$store.commit('data/setCreateTaskErrorMessages', null);
    },
  },
  computed: {
    //更新処理のエラーメッセージを取得するメソッド
    updateTaskErrors () {
      return this.$store.state.data.createTaskErrorMessages;
    },
    //propsで受け取ったタスクが達成済みかどうかを判定しその結果を返すメソッド　true:未達成　false:達成済み
    checkTaskState(){
      if(!this.editForm.deleted_at){
        return true;
      }else{
        return false;
      }
    },
    //scheduleに格納されている予定が達成済みかどうかを判定しその結果を返すメソッド　true:未達成　false:達成済み
    checkScheduleState(){
      if(!this.$store.state.data.schedule.deleted_at){
        return true;
      }else{
        return false;
      }
    },
  },
  watch:{
    //タスク詳細ダイアログが非表示から表示に切り替わった際に実行
    detailFormVisible(newValue){
      if(newValue){
        this.detailMode = true;
        this.editForm = this.detailData;
        this.clearError();
      }
    },
  }
}
</script>
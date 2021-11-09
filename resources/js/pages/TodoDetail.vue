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
        <el-button v-if="detailMode && checkScheduleState" type="primary"@click="detailMode=false"　style="float: right">編集</el-button>
      </el-row>
      <br>
      <el-form ref="form" label-width="120px">
          <el-form-item label="予定名">
            <el-input　 v-model="editForm.name" :disabled="detailMode"></el-input>
          </el-form-item>
          <div v-if="updateScheduleErrors">
            <ul v-if="updateScheduleErrors.name">
              <li :style="errors" v-for="msg in updateScheduleErrors.name" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="優先度">
            <el-input-number v-model="editForm.priority":min="1" :max="10" :disabled="detailMode" ></el-input-number>
          </el-form-item>
          <el-form-item label="期限">
            <el-date-picker
                v-model="editForm.term" 
                type="date" 
                placeholder="期限を選択してください" 
                format="yyyy/MM/dd"
                value-format="yyyy-MM-dd"
                :disabled="detailMode"
              ></el-date-picker>
          </el-form-item>
          <div v-if="updateScheduleErrors">
            <ul v-if="updateScheduleErrors.term">
              <li :style="errors" v-for="msg in updateScheduleErrors.term" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label ="詳細">
              <el-input　type="textarea" v-model="editForm.Text" :disabled="detailMode"></el-input>
            </el-form-item>
            <div v-if="updateScheduleErrors">
            <ul v-if="updateScheduleErrors.Text">
              <li :style="errors" v-for="msg in updateScheduleErrors.Text" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
            <div class="form__button">
              <el-row>
                <el-button v-if="detailMode && checkScheduleState" type="primary"@click="deleteSchedule">予定の完了</el-button>
                <el-button v-if="detailMode" type="danger" @click="openCheckForm" style="float: center">タスクの取り消し</el-button>
                <el-button v-if="detailMode" type="primary"@click="MoveTask"　style="float: right">小タスク一覧</el-button>
                <el-button v-else type="primary"@click="updateSchedule">更新</el-button>
              </el-row>
            </div>
        </el-form>
    </el-dialog>
  </div>
</template>

<script>
import check from './Check.vue'
export default {
  name:'detailForm',
  components:{
    check,　//確認画面ポップアップ用のコンポーネント
  },
  data(){
    return{
      editForm: this.detailData,//詳細データ
      detailMode:true,　//編集モードのon,offを管理する変数 true:詳細画面,false:編集画面
      checkFormVisible:false,　//確認画面のon,offを管理する変数
      checkMessage:"", //確認画面で表示するメッセージを格納するための変数
      errors: {
          color: "red", //エラーメッセージの色の設定
        },
    }
  },

  props:{
    //詳細画面ポップアップのon,offを管理する変数
    detailFormVisible:{
        type:Boolean,
        default:false,
    },
    //propsで受けとったscheduleデータ
    detailData:{
      type:Object,
      default:null
    },
  },
  methods:{
    //ポップアップ画面を閉じる処理
    handleClose() {
        this.$emit('detail-form-close');
        return;
    },
    //スケジュール完了処理
    deleteSchedule(){
        this.$emit('delete-schedule',this.editForm);
    },
    //スケジュール削除処理
    forceDelete(){
      this.$emit('force-delete-schedule', this.editForm);
    },
    //スケジュール更新処理
    updateSchedule(){
      this.$emit('update-schedule',this.editForm);
    },
    //タスク一覧へ移動
    MoveTask(){
      this.$store.commit('data/setSchedule', this.detailData);
      this.$router.push("/task");
    },
    //確認画面を開く
    openCheckForm(){
      this.checkMessage = "本当にこの予定を削除してもよろしいですか。"
      this.checkFormVisible = true;
    },
    //エラーメッセージのリセット
    clearError(){
      this.$store.commit('data/setCreateScheduleErrorMessages', null);
    },
    
  },
  computed: {
    //apiの実行状態を取得
    apiStatus () {
      return this.$store.state.auth.apiStatus;
    },
    //エラーメッセージの取得
    updateScheduleErrors () {
      return this.$store.state.data.createScheduleErrorMessages;
    },
    //カラムのdeleted_atを確認して、達成済みスケジュールかどうかを判定
    checkScheduleState(){
      if(!this.editForm.deleted_at){
        return true;
      }else{
        return false;
      }
    },
  },
  watch:{
    //詳細画面ポップアップが開いたときに実行する
    detailFormVisible(newValue){
      if(newValue){
        this.detailMode = true
        this.editForm = this.detailData;
        this.clearError();
      }
    }
      
  }
}
</script>
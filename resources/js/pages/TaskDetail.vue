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
      editForm: this.detailData,
      detailMode:true,
      checkFormVisible:false,
      checkMessage:"",
      errors: {
          color: "red",
        },
    }
  },

  props:{
      detailFormVisible:{
          type:Boolean,
          default:false,
      },
      detailData:{
        type:Object,
        default:null,
      },
      schedule:{
        type:Object,
        default:null,
      }
  },
  methods:{
    handleClose() {
        this.$emit('detail-form-close');
        return;
    },
    deleteTask(){
      console.log(this.editForm);
        this.$emit('delete-task',this.editForm);
    },
    updateTask(){
      this.$emit('update-task',this.editForm);
    },
    forceDelete(){
      this.$emit('force-delete', this.editForm);
    },
    openCheckForm(){
      this.checkMessage = "本当にこのタスクを削除してもよろしいですか。"
      this.checkFormVisible = true;
    },
    clearError(){
      this.$store.commit('data/setCreateTaskErrorMessages', null);
    },
  },
  computed: {
    apiStatus () {
      return this.$store.state.data.apiStatus;
    },
    updateTaskErrors () {
      return this.$store.state.data.createTaskErrorMessages;
    },
    checkTaskState(){
      if(!this.editForm.deleted_at){
        return true;
      }else{
        return false;
      }
    },
    checkScheduleState(){
      if(!this.schedule.deleted_at){
        return true;
      }else{
        return false;
      }
    },
    
  },
  watch:{
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
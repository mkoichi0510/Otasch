<template>
  <div class="container--small">
      <el-dialog 
        title="詳細" 
        :visible.sync="detailFormVisible" 
        width="70%"
        :before-close="handleClose"
        >
      <el-row>
        <el-button v-if="detailMode" type="primary"@click="detailMode=false">編集</el-button>
      </el-row>
      <el-form ref="form" label-width="120px">
          <el-form-item label="予定名">
            <el-input　 v-model="editForm.name" :disabled="detailMode"></el-input>
          </el-form-item>
          <div v-if="detailErrorMessage">
            <ul v-if="detailErrorMessage.name">
              <li :style="errors" v-for="msg in detailErrorMessage.name" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="優先度">
            <el-input-number v-model="editForm.priority":min="1" :max="10" :disabled="detailMode" ></el-input-number>
          </el-form-item>
          <el-form-item label="期限">
            <el-date-picker v-model="editForm.term" type="date" :disabled="detailMode"></el-date-picker>
          </el-form-item>
          <div v-if="detailErrorMessage">
            <ul v-if="detailErrorMessage.term">
              <li :style="errors" v-for="msg in detailErrorMessage.term" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label ="詳細">
              <el-input　type="textarea" v-model="editForm.Text" :disabled="detailMode"></el-input>
            </el-form-item>
            <div v-if="detailErrorMessage">
            <ul v-if="detailErrorMessage.Text">
              <li :style="errors" v-for="msg in detailErrorMessage.Text" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
            <div class="form__button">
              <el-row>
                <el-button v-if="detailMode" type="primary"@click="deleteSchedule">予定の完了</el-button>
                <el-button v-else type="primary"@click="updateSchedule">更新</el-button>
              </el-row>
            </div>
        </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name:'detailForm',
  data(){
    return{
      editForm: this.detailData,
      instantName: null,
      instantPriority: null,
      instantTerm:null,
      instantText:null,
      detailMode:true,
      checkError:false,
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
        default:null
      },
      detailErrorMessage:{
        type:Object,
        default:false,
      }
      
  },
  methods:{
    handleClose() {
        this.$emit('detail-form-close')
        this.$emit('detail-error-message')
        if(this.checkError){
          this.setBackData()
        }
        return
    },
    deleteSchedule(){
        this.$emit('delete-schedule',this.editForm)
    },
    updateSchedule(){
      this.editForm.term = this.transformDate(this.editForm.term);
      this.$emit('update-schedule',this.editForm)
    },
    setBackData(){
      this.editForm.name = this.instantName
      this.editForm.priority = this.instantPriority
      this.editForm.term = this.instantTerm
      this.editForm.Text = this.instantText
    },
    setinstanceData(){
      this.instantName = this.editForm.name
      this.instantPriority = this.editForm.priority
      this.instantTerm = this.editForm.term
      this.instantText = this.editForm.Text
    },
    transformDate(date){
        date = new Date(date);
        var formattedDate = [date.getFullYear(),('0' + (date.getMonth() + 1)).slice(-2),('0' + date.getDate()).slice(-2)].join('/');
        return formattedDate;
    },
    
    
  },
  computed: {
    apiStatus () {
      return this.$store.state.auth.apiStatus
    }
  },
  watch:{
      detailData(newValue){
        this.editForm = newValue
        this.setinstanceData()
      },
      detailErrorMessage(){
          this.checkError = true
      },
      detailFormVisible(){
        if(!this.detailFormVisible){
          this.detailMode = true
          this.checkError = false
        }
      }
      
  }
}
</script>
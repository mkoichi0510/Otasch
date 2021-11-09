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
                <el-button type="primary"@click="registerSchedule">Create</el-button>
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
      createForm: {
        name: '',
        Text: '',
        priority: 1,
        term: null,
      },
      errors: {
          color: "red",
        },
    }
  },
  props:{
      createFormVisible:{
          type:Boolean,
          default:false,
      },
  },
  methods:{
    handleClose() {
        this.$emit('create-form-close');
        return;
    },
    registerSchedule(){
        this.$emit('register-schedule',this.createForm);
    },
    initialzleRegisterForm(){
      this.createForm.name = '';
      this.createForm.Text = '';
      this.createForm.priority = 1;
      this.createForm.term = null;
    },
    clearError(){
      this.$store.commit('data/setCreateScheduleErrorMessages', null);
    },
  },
  computed: {
    apiStatus () {
      return this.$store.state.data.apiStatus;
    },
     createScheduleErrors () {
      return this.$store.state.data.createScheduleErrorMessages;
    }
  },
  watch:{
      createFormVisible(newvalue){
        console.log("create");
        if(newvalue){
          this.clearError();
        　this.initialzleRegisterForm();
        }
      }
  }
}
</script>


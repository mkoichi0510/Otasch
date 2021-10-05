<template>
  <div class="container--small">
      <el-dialog 
        title="Tips" 
        :visible.sync="createFormVisible" 
        width="70%"
        :before-close="handleClose"
        >
      <el-form ref="form" label-width="120px">
          <el-form-item label="name">
            <el-input v-model="createForm.name"></el-input>
          </el-form-item>
          <div v-if="createErrorMessage">
            <ul v-if="createErrorMessage.name">
              <li :style="errors" v-for="msg in createErrorMessage.name" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="priority">
            <el-input-number v-model="createForm.priority":min="1" :max="10"></el-input-number>
          </el-form-item>
          <el-form-item label ="term">
              <el-date-picker v-model="createForm.term" type="date" placeholder="Pick a day"></el-date-picker>
            </el-form-item>
          <div v-if="createErrorMessage">
            <ul v-if="createErrorMessage.term">
              <li :style="errors" v-for="msg in createErrorMessage.term" :key="msg" >{{ msg }}</li>
            </ul>
          </div>
          <el-form-item label="Text">
            <el-input type="textarea" v-model="createForm.Text"></el-input>
          </el-form-item>
          <div v-if="createErrorMessage">
            <ul v-if="createErrorMessage.Text">
              <li :style="errors" v-for="msg in createErrorMessage.Text" :key="msg" >{{ msg }}</li>
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
        term: null
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
      createErrorMessage:{
        type:Object,
        default:false,
      }
  },
  methods:{
    handleClose() {
        this.$emit('create-form-close');
        this.$emit('reset-error-message')
        return
    },
    registerSchedule(){
        this.createForm.term = this.transformDate(this.createForm.term);
        this.$emit('register-schedule',this.createForm)
        //this.initialzleRegisterForm();
    },
    transformDate(date){
        date = new Date(date);
        var formattedDate = [date.getFullYear(),('0' + (date.getMonth() + 1)).slice(-2),('0' + date.getDate()).slice(-2)].join('/');
        return formattedDate;
    },
    initialzleRegisterForm(){
      this.createForm.name = '';
      this.createForm.Text = '';
      this.createForm.priority = 1;
      this.createForm.term = null;
    }
  },
  computed: {
    apiStatus () {
      return this.$store.state.auth.apiStatus
    }
  },
  watch:{
      createFormVisible(){
        this.initialzleRegisterForm();
      }
  }
}
</script>


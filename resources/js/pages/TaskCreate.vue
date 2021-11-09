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
  name:'createForm',
  data () {
      
    return {
      createForm: {
        name: '',
        Text: '',
        priority: 1,
        schedule_id: null,
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
    registerTask(){
      console.log(this.createForm);
        this.$emit('register-task',this.createForm);
    },
    initialzleCreateForm(){
      this.createForm.name = '';
      this.createForm.Text = '';
      this.createForm.priority = 1;
    },
    clearError(){
      this.$store.commit('data/setCreateTaskErrorMessages', null);
    },
  },
  created(){
    // this.clearError();
  },
  computed: {
    apiStatus () {
      return this.$store.state.data.apiStatus;
    },
    createTaskErrors () {
      return this.$store.state.data.createTaskErrorMessages;
    }
  },
  watch:{
      createFormVisible(newValue){
        if(newValue){
          this.clearError();
          this.initialzleCreateForm();
        }
      }
  }
}
</script>


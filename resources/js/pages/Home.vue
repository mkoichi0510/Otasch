<template>
  <div class="body">
    <createForm 
    :createFormVisible='createDialogVisible'
    :createErrorMessage='createErrors'
    @register-schedule="register" 
    @create-form-close="createDialogVisible=false"
    @reset-error-message="createErrors=null"
    >
    </createForm>
    <detailForm 
    :detailFormVisible='detailDialogVisible'
    :detailData='detailData'
    :detailErrorMessage="detailErrors"
    @delete-schedule="softDelete" 
    @update-schedule='updateSchedule'
    @detail-form-close="detailDialogVisible=false"
    @detail-error-message="detailErrors=null"
    >
    </detailForm>
    <sortForm
      :sortFormVisible='sortDialogVisible'
      :sortSchedules='sortSchedules'
      @sort-form-close="sortDialogVisible=false"
    >
    </sortForm>
    <todoList 
    :todoLists='schedules'
    :showDetail='showDetail'
    :changeCreateVisibleState = 'changeCreateVisibleState'
    :sortFormVisible='sortDialogVisible'
    @sort-form-open="sortDialogVisible=true"
    ></todoList>
  </div>
</template>

<script>
import createForm from './TodoCreate.vue'
import detailForm from './TodoDetail.vue'
import todoList from './TodoList.vue'
import sortForm from './TodoSort'
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

export default {
  components:{
    createForm,
    detailForm,
    todoList,
    sortForm
  },
  data () {
    return {
        schedules:null,
        createDialogVisible: false,
        detailDialogVisible: false,
        todoListsVisible: false,
        sortDialogVisible: false,
        detailData: {
          name: '',
          detail: '',
          priority: '',
          term: null,
          id:1
        },
        body:{
          position: "relative",
          paddingBottom: "60px",
          boxSizing: "border-box",
          minHeight: "100vh"
        },
        createErrors:null,
        detailErrors:null,
    }
  },
  methods:{
    //データの取得
    getTodo(){
        axios.get('/schedules').then((response)=>{
        this.schedules = response.data;
        console.log(this.schedules);
      })
    },
    //データの新規登録
    async register(data){
      console.log("register");
      const response = await axios.post('/schedules',data)
      console.log(response.status)
      if(response.status === UNPROCESSABLE_ENTITY) {
        this.createErrors = response.data.errors
        return false
      }
      if(response.status !== CREATED){
        this.$store.commit('error/setCode', response.status)
        return false
      }
      　
        this.getTodo()
        this.createDialogVisible = false
        this.createErrors = null
    },
    showDetail(data){
      this.detailData = data;
      console.log(this.detailData);
      this.detailDialogVisible = true;
    },
    softDelete(data){
      axios.delete(`/schedules/${data.id}`).then(()=>{
        this.getTodo();
        this.detailDialogVisible = false;
      })
      
    },
    //データの更新
    async updateSchedule(data){
      const response = await axios.put(`/schedules/${data.id}`,data)
      if(response.status === UNPROCESSABLE_ENTITY) {
        this.detailErrors = response.data.errors
        return false
      }
      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status)
      }
        this.getTodo();
        this.detailDialogVisible = false;
        this.detailErrors = null
    },
    changeCreateVisibleState(data){
      this.createDialogVisible = data;
    },
    sortSchedules(data1,data2){
      if(data1 == 1){
        if(data2 == 1){
          this.schedules.sort(function(a, b) {
            if (a.priority < b.priority) {
              return -1;
            } else {
              return 1;
            }
            
          });
        } else {
          this.schedules.sort(function(a, b) {
            if (a.priority < b.priority) {
              return 1;
            } else {
              return -1;
            }
          });
        } 
      }else if(data1 == 2){
        if(data2 == 1){
          this.schedules.sort(function(a, b) {
            if (a.term < b.term) {
              return -1;
            } else {
              return 1;
            }
          });
        } else {
          this.schedules.sort(function(a, b) {
            if (a.term < b.term) {
              return 1;
            } else {
              return -1;
            }
          });
        } 
      }
      
      console.log(this.schedules);
      this.sortDialogVisible = false;
    },
    
  },
  mounted(){
    this.getTodo();
  },
}
</script>
<style>
  .body{
    padding:0; 
    margin:0;
    height: 100%;
  }
</style>
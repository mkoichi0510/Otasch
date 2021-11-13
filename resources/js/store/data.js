import { OK, CREATED, UNPROCESSABLE_ENTITY} from '../util'

const state = {
    apiStatus: null,
    createTaskErrorMessages : null,
    createScheduleErrorMessages : null, 
    schedules:null,
    schedule:null,
    tasks:null,
}

const getters = {
  
}

const mutations = {
  setApiStatus (state, status) {
    state.apiStatus = status;
  },
  setTasks (state, tasks) {
    state.tasks = tasks;
  },
  setSchedules (state, schedules) {
    state.schedules = schedules;
  },
  setSchedule (state, schedule) {
    state.schedule = schedule;
  },
  setCreateTaskErrorMessages (state, messages) {
    state.createTaskErrorMessages = messages;
  },
  setCreateScheduleErrorMessages (state, messages) {
    state.createScheduleErrorMessages = messages;
  },
}

const actions = {
  //タスク登録
  async registerTask (context, data) {
    context.commit('setApiStatus', null);
    const response = await axios.post('/api/tasks/register', data);
    console.log(response.status);
    if(response.status === CREATED){
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setCreateTaskErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  //タスクの取得
  async getTask(context, id){
    context.commit('setApiStatus', null);
    //未達成予定のタスクを取得する場合
    const response = await axios.get(`/api/tasks/getdata/${id}`);
    console.log(response.data);
    if(response.status === OK){
      context.commit('setTasks', response.data);
      context.commit('setApiStatus', true);
      return false
    }
    
    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){
      //context.commit('setUpdateErrorMessages', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //達成済みタスクを含めすべてのタスクの取得
  async getAllTask(context, id){
    context.commit('setApiStatus', null);
    const response = await axios.get(`/api/tasks/getalldata/${id}`);
    if(response.status === OK){
      context.commit('setTasks', response.data);
      context.commit('setApiStatus', true);
      return false
    }
    
    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){
      //context.commit('setUpdateErrorMessages', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //達成済みタスクのみ取得
  async getClearTask(context, id){
    context.commit('setApiStatus', null);
    const response = await axios.get(`/api/tasks/getcleardata/${id}`);
    if(response.status === OK){
      context.commit('setTasks', response.data);
      context.commit('setApiStatus', true);
      return false
    }
    
    context.commit('setApiStatus', false);
    if(response.status === UNPROCESSABLE_ENTITY){
      //context.commit('setUpdateErrorMessages', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //タスクの更新
  async updateTask(context, data){
    context.commit('setApiStatus', null);
    const response = await axios.put(`/api/tasks/update/${data.id}`, data);
    if(response.status === OK){
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false);
    if(response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setCreateTaskErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //タスクの論理削除
  async deleteTask(context, data){
    context.commit('setApiStatus', null);
    const response = await axios.delete(`/api/tasks/delete/${data.id}`, data);
    if(response.status === OK){
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false);
    if(response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setCreateTaskErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  //タスクの物理削除
  async forceDeleteTask(context, data){
    context.commit('setApiStatus', null);
    console.log(data.id);
    const response = await axios.post('/api/tasks/forcedelete', data);
    if(response.status === OK){
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false);
    if(response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setCreateTaskErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  /*予定関係のメソッド*/
  
  //予定登録
  async registerSchedule (context, data) {
    context.commit('setApiStatus', null);
    const response = await axios.post('/api/schedules/register', data);
    console.log(response.status);
    if(response.status === CREATED){
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setCreateScheduleErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //予定の取得
  async getSchedules(context){
    context.commit('setApiStatus', null);
    const response = await axios.get('/api/schedules/getdata');
    console.log(response.data);
    if(response.status === OK){
      context.commit('setSchedules', response.data);
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){
      //context.commit('setUpdateErrorMessages', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //達成済み予定を含めすべての予定の取得
  async getAllSchedule(context){
    context.commit('setApiStatus', null);
    const response = await axios.get('/api/schedules/getallschedule');
    console.log(response.data);
    if(response.status === OK){
      context.commit('setSchedules', response.data);
      context.commit('setApiStatus', true);
      return false
    }
    
    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){
      //context.commit('setUpdateErrorMessages', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //達成済み予定のみ取得
  async getClearSchedule(context){
    context.commit('setApiStatus', null);
    const response = await axios.get('/api/schedules/getsoftdelete');
    console.log(response.data);
    if(response.status === OK){
      context.commit('setSchedules', response.data);
      context.commit('setApiStatus', true);
      return false
    }
    
    context.commit('setApiStatus', false);
    if(response.status === UNPROCESSABLE_ENTITY){
      //context.commit('setUpdateErrorMessages', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //予定の更新
  async updateSchedule(context, data){
    context.commit('setApiStatus', null);
    const response = await axios.put(`/api/schedules/update/${data.id}`, data);
    if(response.status === OK){
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false);
    if(response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setCreateScheduleErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //予定の論路削除
  async deleteSchedule(context, data){
    context.commit('setApiStatus', null);
    const response = await axios.delete(`/api/schedules/delete/${data.id}`, data);
    if(response.status === OK){
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false);
    if(response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setCreateScheduleErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  
  //予定の物理削除
  async forceDeleteSchedule(context, data){
    context.commit('setApiStatus', null);
    const response = await axios.post('/api/schedules/forcedelete', data);
    console.log(response.status);
    if(response.status === OK){
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false);
    if(response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setCreateScheduleErrorMessages', response.data.errors);
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
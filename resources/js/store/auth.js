import { OK, CREATED, UNPROCESSABLE_ENTITY} from '../util'

const state = {
    user: null,
    apiStatus: null,
    loginErrorMessages: null,
    registerErrorMessages: null,
    updateErrorMessages:null,
    createTaskErrorMessages : null,
    line_client_id : null,
    line_client_callback : null,
    line_client_secret : null,
}

const getters = {
    check: state => !! state.user,
    username: state => state.user ? state.user.name : '',
    password: state => state.user ? state.user.password : '',
    mail: state => state.user ? state.user.email : '',
    checkLineLogin: state => state.user ? state.user.sns_id : '', 
}

const mutations = {
  setUser (state, user) {
    state.user = user;
  },
  setApiStatus (state, status) {
    state.apiStatus = status;
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages;
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages;
  },
  setUpdateErrorMessages (state, messages) {
    state.updateErrorMessages = messages;
  },
  setCreateTaskErrorMEssages (state, messages) {
    state.createTaskErrorMessages = messages;
  },
  setLineClientData(state, data){
    console.log(data.client_id);
    state.line_client_id = data.client_id;
    state.line_client_callback = data.callback;
    state.line_client_secret = data.client_secret;
  }
}

const actions = {
  //通常会員登録
  async register (context, data) {
    context.commit('setApiStatus', null);
    const response = await axios.post('/api/register', data);
    
    if(response.status === CREATED){
      context.commit('setUser', response.data);
      context.commit('setApiStatus', true);
      return false;
    }
    
    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setRegisterErrorMessages', response.data.errors)
    }else{
      context.commit('error/setCode', response.status, {root: true})
    }
  },
  //ラインアカウントでの会員登録
  async registerLineAccount (context, data) {
    context.commit('setApiStatus', null);
    const response = await axios.post('/api/linelogin/register', data);
    // const check = await axios.post('/api/linelogin/check', response.data);
    // let response2;
    // console.log(check.data);
    // if(check.status === OK){
    //   console.log("login");
    //   console.log(check.data);
    //   response2 = await axios.post('/api/login', check.data);
    // }
    // else if(check.status === CREATED){
    const response2 = await axios.post('/api/register', response.data);
    //}
    console.log(response2.status);
    
    //登録またはログイン成功時
    if(response2.status === CREATED || response2.status === OK){
      context.commit('setUser', response2.data);
      context.commit('setApiStatus', true);
      console.log(response2.data);
      return false;
    }
    
    context.commit('setApiStatus', false)
    if(response2.status === UNPROCESSABLE_ENTITY){
      context.commit('setRegisterErrorMessages', response2.data.errors)
    }else{
      context.commit('error/setCode', response2.status, {root: true})
    }
  },
  
  //通常ログイン
  async login (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/login', data)
    .catch(err => err.response || err)
    if (response.status === OK) {
      context.commit('setUser', response.data)
      context.commit('setApiStatus', true)
      console.log(response.data);//ユーザーオブジェクトを渡せればOK
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
  },
  
  //ログアウト
  async logout (context) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/logout')
    
    if(response.status === OK){
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      return false
    }
    
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, {root: true })
  },
  
  //ラインアカウントの物理削除
  async deleteLineAccount (context) {
    context.commit('setApiStatus', null);
    const response = await axios.post('/api/linelogin/delete');
    
    if(response.status === OK){
      context.commit('setApiStatus', true);
      context.commit('setUser', null);
      return false;
    }
    
    context.commit('setApiStatus', false);
    context.commit('error/setCode', response.status, {root: true });
  },
  
  //Lineクライアントのデータの設定
  async getLineClientData(context){
    context.commit('setApiStatus', null);
    const response = await axios.get(`/api/linelogin/data`);
    console.log(response.data);
    if(response.status === OK){
      context.commit('setLineClientData', response.data);
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
  
  //ログインユーザーチェック
  async currentUser (context) {
    console.log("ログインユーザーチェック");
    context.commit('setApiStatus', null)
    const response = await axios.get('/api/user')
    console.log(response.data);
    const user = response.data || null
    
    if(response.status === OK){
      context.commit('setUser', user)
      context.commit('setApiStatus', true)
      return false
    }
    
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true} )
  },
  
  //ユーザー名の変更
  async changeUserName(context, data){
    context.commit('setApiStatus', null);
    const response = await axios.put('/api/update', data);
    if(response.status === OK){
      context.commit('setUser', response.data)
      context.commit('setApiStatus', true)
      return false
    }
    
    context.commit('setApiStatus', false)
    if(response.status === UNPROCESSABLE_ENTITY){
      context.commit('setUpdateErrorMessages', response.data.errors)
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
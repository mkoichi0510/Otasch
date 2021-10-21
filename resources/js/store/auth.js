import { OK, CREATED, UNPROCESSABLE_ENTITY} from '../util'

const state = {
    user: null,
    apiStatus: null,
    loginErrorMessages: null,
    registerErrorMessages: null
}

const getters = {
    check: state => !! state.user,
    username: state => state.user ? state.user.name : '',
    checkLineLogin: state => !! state.user.sns_id 
}

const mutations = {
  setUser (state, user) {
    state.user = user
  },
  setApiStatus (state, status) {
    state.apiStatus = status
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages
  }
}

const actions = {
  //通常会員登録
  async register (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/register', data)
    
    if(response.status === CREATED){
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
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
    const response2 = await axios.post('/api/register', response.data);
    console.log(response2.status);
    if(response2.status === CREATED){
      context.commit('setApiStatus', true);
      context.commit('setUser', response2.data);
      console.log(response2.data);
      return false;
    }
    
    //既に登録済みの場合
    if (response2.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response2.data)
      return false
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
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
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
  
  //ラインアカウントとの連携の解除
  async logoutLine (context) {
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
  
  //ログインユーザーチェック
  async currentUser (context) {
    console.log("ログインユーザーチェック");
    context.commit('setApiStatus', null)
    const response = await axios.get('/api/user')
    console.log(response.data);
    const user = response.data || null
    
    if(response.status === OK){
      context.commit('setApiStatus', true)
      context.commit('setUser', user)
      return false
    }
    
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true} )
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
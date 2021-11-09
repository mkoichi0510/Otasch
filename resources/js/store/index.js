import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'
import data from './data'

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    auth,//認証関連
    error,//エラー関連
    data,//データ関連
  }
});

export default store;
import './bootstrap';
import Vue from 'vue';
// ルーティングの定義をインポートする
import router from './router';
// ルートコンポーネントをインポートする
import App from './App.vue';
import store from './store/index';
import ElementUI from 'element-ui';
import axios from 'axios';
import VueAxios from 'vue-axios';
import VuePaginate from 'vue-paginate';
import 'element-ui/lib/theme-chalk/index.css';

Vue.use(ElementUI);
Vue.use(VuePaginate);

const createApp = async () => {
  await store.dispatch('auth/currentUser');
  
  new Vue({
    el: '#app',
    router, // ルーティングの定義を読み込む
    store,
    components: { App }, // ルートコンポーネントの使用を宣言する
    template: '<App />' // ルートコンポーネントを描画する
  })
}

createApp();

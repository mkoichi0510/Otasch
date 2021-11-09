import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Form from './pages/Form.vue'
import Login from './pages/Login.vue'
import store from './store' 
import SystemError from './pages/errors/System.vue'
import TodoDetail from './pages/TodoDetail.vue'
import Home from './pages/Home.vue'
import Preview from './pages/PreviewList.vue'
import Task from './pages/PreviewTask.vue'
import Account from './pages/Account.vue' 
import Log from './pages/Log.vue' 
import Top from './pages/Top.vue'
// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
  {
    path: '',
    component: Top,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('home');
      } else {
        next();
      }
    }
  },
  {
    path: '/home',
    component: Home,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next();
      } else {
        next('login')
      }
    }
  },
  {
    path: '/login',
    component: Form,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('home');
      } else {
        next();
      }
    }
  },
  {
    path: '/preview',
    component: Preview,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next();
      } else {
        next('login');
      }
    }
  },
  {
    path: '/account',
    component:Account,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next();
      } else {
        next('login');
      }
    }
  },
  {
    path: '/log',
    component:Log,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next();
      } else {
        next('login');
      }
    }
  },
  {
    path: '/task',
    name: 'task',
    component:Task,
    // props:true,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next();
      } else {
        next('login');
      }
    }
  },
  {
    path: '/500',
    component: SystemError
  },
  // {
  //   path:'/home/:id',
  //   name:"schedule-url",
  //   component: TodoDetail,
  //   props:true
  // }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history',
  routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router
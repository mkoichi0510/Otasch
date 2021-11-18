<template>
  <div class="body" v-loading.fullscreen.lock="loading">
    <div class="container--small" v-if="schedules">
      <div class="components">
        <createForm 
        :createFormVisible='createDialogVisible'
        @register-schedule="register" 
        @create-form-close="createDialogVisible=false"
        >
        </createForm>
        <detailForm 
        :detailFormVisible='detailDialogVisible'
        :detailData='detailData'
        @delete-schedule="softDelete" 
        @force-delete-schedule="forceDelete"
        @update-schedule='updateSchedule'
        @detail-form-close="detailDialogVisible=false"
        >
        </detailForm>
        <sortForm
          :sortFormVisible='sortDialogVisible'
          :sortSchedules='sortSchedules'
          @sort-form-close="sortDialogVisible=false"
        >
        </sortForm>
      </div>
      <div class="body">
        <h1>
          <span>{{scheduleLabel}}予定一覧</span>
          <el-dropdown>
            <span class="el-dropdown-link">
              表示切替<i class="el-icon-arrow-down el-icon--right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item @click.native="schedules=clearSchedules,scheduleLabel='達成済み'">達成済み予定</el-dropdown-item>
              <el-dropdown-item @click.native="getTodo(),scheduleLabel='未達成'">未達成予定</el-dropdown-item>
              <el-dropdown-item @click.native="schedules=allSchedules, scheduleLabel='全'">全予定</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <el-button type="primary"@click="sortDialogVisible=true" v-if="schedules.length > 1">並び替え</el-button>
          <el-button type="primary"@click="createDialogVisible=true" style="float: right">予定の新規作成</el-button>
        </h1>
        <ul v-if=schedules>
          <li v-for="(schedule, i) in paginateSchedules" :key="i">
            <el-link type="primary"@click="showDetail(schedule)">
              <h2 class='title'>{{ schedule.name }}</h2>
            </el-link>
          </li>
        </ul>
        <div v-if="schedules.length < 1">
          <el-empty　description="NoData"></el-empty>
        </div>
        <el-pagination
          background
          layout="prev, pager, next"
          :current-page.sync="currentPage"
          :page-size="perPage"
          :total="schedules.length">
        </el-pagination>
      </div>
    </div>
  </div>
</template>

<script>
import createForm from './TodoCreate.vue'
import detailForm from './TodoDetail.vue'
import sortForm from './TodoSort'

export default {
  components:{
    createForm,
    detailForm,
    sortForm,
  },
  data () {
    return {
        schedules:null, //画面に表示する予定一覧のデータを格納する変数
        createDialogVisible: false, //新規予定作成ダイアログの表示非表示を管理する変数
        detailDialogVisible: false, //予定の詳細画面の表示非常時を管理する変数
        sortDialogVisible: false, //予定のソートを行うダイアログの表示非表示を管理する変数
        scheduleLabel:"未達成", //画面に表示する予定の種類を表す文字列を格納する変数
        allSchedules: null, //全予定を格納する変数
        clearSchedules: null, //達成済み予定を格納する変数
        loading : false, //画面のローディング表示を管理する変数
        //ぺジネーション用
        currentPage : 1,//現在のページ 
        perPage: 10, //1ページ毎の表示件数
        //詳細画面にpropsで渡すデータ
        detailData: {
          name: '', //予定名
          detail: '', //詳細
          priority: '', //優先度
          term: null, //期限
          id:null, //予定id
        },
    }
  },
  methods:{
    //未完了予定の取得
    async getTodo(){
      await this.$store.dispatch('data/getSchedules');
      
      if(this.apiStatus){
        this.schedules = this.$store.state.data.schedules;
        this.scheduleLabel = "未達成";
      }
    },
    //達成済みを含めたすべてのスケジュールの取得
    async getAllSchedule(){
      await this.$store.dispatch('data/getAllSchedule');
      
      if(this.apiStatus){
        this.allSchedules = this.$store.state.data.schedules;
      }
    },
     //達成済み予定のみ取得
    async getClearSchedule(){
      await this.$store.dispatch('data/getClearSchedule');
      
      if(this.apiStatus){
        this.clearSchedules = this.$store.state.data.schedules;
      }
    },
    //全予定情報の更新
    async updateScheduleData(){
      await this.getTodo();
      this.loading = false;//ローディング表示を消す
      await this.getAllSchedule();
      await this.getClearSchedule();
    },
    //予定の新規登録
    async register(data){
      await this.$store.dispatch('data/registerSchedule', data);
      
      if(this.apiStatus) {
        //予定の作成後に予定一覧を更新
        this.updateScheduleData();
        this.createDialogVisible = false;
      }
    },
    //propsで渡すデータの設定
    showDetail(data){
      this.detailData = data;
      this.detailDialogVisible = true;
    },
    //予定の達成処理
    async softDelete(data){
      await this.$store.dispatch('data/deleteSchedule', data);
      
      if (this.apiStatus) {
        //予定の達成処理後に予定一覧を再取得
        this.updateScheduleData();
        this.detailDialogVisible = false;
      }
    },
    //予定の取り消し
    async forceDelete(data){
      await this.$store.dispatch('data/forceDeleteSchedule', data);
      
      if (this.apiStatus) {
        //予定削除後にタスク一覧を更新
        this.updateScheduleData();
        this.detailDialogVisible = false;
      }
    },
    //データの更新
    async updateSchedule(data){
      await this.$store.dispatch('data/updateSchedule', data);
      
      if (this.apiStatus) {
        this.detailDialogVisible = false;
      }
      //スケジュール更新後にスケジュール一覧を更新
      this.updateScheduleData();
    },
    //スケジュールのソート　type:1 優先度でソート:2 期限でソート　oder:1 昇順でソート:2 降順でソート
    sortSchedules(type,order){
      if(type == 1){
        if(order == 1){
          this.schedules.sort(this.comparePrioritytUp);
        } else {
          this.schedules.sort(this.comparePrioritytDown);
        } 
      }else if(type == 2){
        if(order == 1){
          this.schedules.sort(this.compareTermUp);
        } else {
          this.schedules.sort(this.compareTermDown);
        } 
      }
    
      this.sortDialogVisible = false;
    },
    //昇順でタスクを優先度順にソート
    comparePrioritytUp(a, b) {
      return a.priority - b.priority;
    },
    //降順でタスクを優先度順にソート
    comparePrioritytDown(a, b) {
      return b.priority - a.priority;
    },
    //昇順でタスクを期限順にソート
    compareTermUp(a, b) {
      return new Date(a.term).getTime() - new Date(b.term).getTime();
    },
    //降順でタスクを期限順にソート
    compareTermDown(a, b) {
      return new Date(b.term).getTime() - new Date(a.term).getTime();
    },
  },
  
  created(){
    this.loading = true;//ローディング表示を入れる
    this.updateScheduleData();
    //現在のページを表す値の設定
    this.$store.commit('data/setPageIndex', "3");
  },
  
  computed: {
     //1ページあたりのschedulesデータを返す
    paginateSchedules() {
      //第1引数には取り出しの開始位置、第2引数には取り出しを終える直前の位置を渡す
      return this.schedules.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
    },
    //サーバー側に投げた処理が成功したかどうかを返す
    apiStatus () {
      return this.$store.state.data.apiStatus;
    },
  },
}
</script>

<style>
  .el-dropdown-link {
    cursor: pointer;
    color: #409EFF;
  }
  .el-icon-arrow-down {
    font-size: 12px;
  }
</style>
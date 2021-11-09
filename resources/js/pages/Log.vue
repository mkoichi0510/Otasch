<template>
  <div class="body">
    <div class="scheduleList" v-if="schedules">
      <p></p>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <h1>今日の完了済み予定</h1>
        </div>
        <div class="result" v-if="clearScheduleToday">
          <ul>
            <li v-for="(schedule, i) in paginateSchedulesToday" :key="i">
              <el-link type="primary"@click="MoveTask(schedule);">
                <h2 class='title'>{{ schedule.name }}</h2>
              </el-link>
            </li>
          </ul>
          <div v-if="clearScheduleToday.length < 1">
            <el-empty　description="NoData"></el-empty>
          </div>
          <el-pagination
            background
            layout="prev, pager, next"
            :current-page.sync="currentPageSchedule"
            :page-size="perPageSchedule"
            :total="clearScheduleToday.length">
          </el-pagination>
        </div>
        <div v-else>
          <el-empty　description="NoData"></el-empty>
        </div>
      </el-card>
      <p></p>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <h1>過去の完了済み予定</h1>
        </div>
        <div class="result" v-if="clearScheduleBefore">
          <h2>
            <span>{{ getClearMonth(selectDate) }}達成予定</span>
            <span>
              <el-date-picker
                v-model="selectDate"
                type="month"
                format="yyyy/MM"
                value-format="yyyy-MM"
                placeholder="Pick a month"
                >
              </el-date-picker>
            </span>
          </h2>
          <ul>
            <li v-for="(schedule, i) in paginateSchedulesBefore" :key="i">
              <!--<h2>達成日：{{getClearDate(schedule.deleted_at)}}</h2>-->
              <el-link type="primary"@click="MoveTask(schedule);">
                <h2>{{ schedule.name }}</h2>
              </el-link>
            </li>
          </ul>
          <div v-if="clearScheduleBefore.length < 1">
            <el-empty　description="NoData"></el-empty>
          </div>
          <el-pagination
            background
            layout="prev, pager, next"
            :current-page.sync="currentPageScheduleBefore"
            :page-size="perPageScheduleBefore"
            :total="clearScheduleBefore.length">
          </el-pagination>
        </div>
      </el-card>
      <p></p>
    </div>
    <div class="taskList" v-if="tasks">
          <el-card class="box-card">
            <div class="result">
              <ul>
                <li v-for="(task, i) in paginateTasks" :key="i">
                  <el-link type="primary"@click=""><h2 class='title'>{{ task.name }}</h2></el-link>
                </li>
              </ul>
              <el-pagination
                background
                layout="prev, pager, next"
                :current-page.sync="currentPageTask"
                :page-size="perPageTask"
                :total="tasks.length">
              </el-pagination>
            </div>
          </el-card>
          <p></p>
        </div>
  </div>
</template>

<script>
import createForm from './TodoCreate.vue'
import detailForm from './TodoDetail.vue'
import sortForm from './TodoSort'
import lineApi from './Line.vue'
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

export default {
  components:{
    createForm,
    detailForm,
    sortForm,
    lineApi
  },
  data () {
    return {
      schedules:null,
      clearScheduleToday:null,
      clearScheduleBefore:null,
      tasks: null,
      detailDialogVisibleTask:false,
      detailDialogVisibleSchedule:false,
      detailDataSchedule: {
          name: '',
          detail: '',
          priority: '',
          term: null,
          id:null,
          deleted_at: null,
      },
      detailDataTask: {
          name: '',
          detail: '',
          priority: '',
          id:null,
          deleted_at: null,
      },
      //ぺジネーション用
      currentPageSchedule : 1,//現在のページ 
      perPageSchedule: 5, //1ページ毎の表示件数
      currentPageScheduleBefore : 1,//現在のページ 
      perPageScheduleBefore: 5, //1ページ毎の表示件数
      currentPageTask : 1,//現在のページ 
      perPageTask: 5, //1ページ毎の表示件数
      selectDate:new Date(),
    }
  },
  computed: {
    apiStatus () {
      return this.$store.state.data.apiStatus;
    },
    paginateSchedules() {
      //第1引数には取り出しの開始位置、第2引数には取り出しを終える直前の位置を渡す
      return this.schedules.slice((this.currentPageSchedule - 1) * this.perPageSchedule, this.currentPageSchedule * this.perPageSchedule);
    },
    paginateSchedulesToday() {
      //第1引数には取り出しの開始位置、第2引数には取り出しを終える直前の位置を渡す
      return this.clearScheduleToday.slice((this.currentPageSchedule - 1) * this.perPageSchedule, this.currentPageSchedule * this.perPageSchedule);
    },
    paginateSchedulesBefore() {
      //第1引数には取り出しの開始位置、第2引数には取り出しを終える直前の位置を渡す
      return this.clearScheduleBefore.slice((this.currentPageScheduleBefore - 1) * this.perPageScheduleBefore, this.currentPageScheduleBefore * this.perPageScheduleBefore);
    },
    paginateTasks() {
        return this.tasks.slice((this.currentPageTask - 1) * this.perPageTask, this.currentPageTask * this.perPageTask);
    },
  },
  methods:{
    //達成済み予定のみ取得
    async getClearSchedule(){
      await this.$store.dispatch('data/getClearSchedule');
      if(this.apiStatus){
        this.schedules = this.$store.state.data.schedules;
      }
    },
    //達成済みタスクのみ取得
    async getClearData(){
      await this.$store.dispatch('data/getClearTask', this.schedule.id);
      if(this.apiStatus){
        this.tasks = this.$store.state.data.tasks;
      }
    },
    
    //タスク一覧へ移動
    MoveTask(data){
      this.$store.commit('data/setSchedule', data);
      this.$router.push("/task");
    },
    //propsで受け渡すデータの設定とダイアログの表示
    showDetailSchedule(data){
      this.detailDataSchedule = data;
      this.detailDialogVisibleSchedule = true;
    },
    //propsで受け渡すデータの設定とダイアログの表示
    showDetailTask(data){
      this.detailDataTask = data;
      this.detailDialogVisibleTask = true;
    },
    //当日に達成した予定を格納するメソッド
    setTodayClearSchedule(){
      if(!this.schedules){
        return;
      }
      let today = new Date();
      this.clearScheduleToday = this.schedules.filter(schedule => {
          let scheduleDay = new Date(schedule.deleted_at);
          return scheduleDay.getFullYear() == today.getFullYear() && scheduleDay.getMonth() == today.getMonth() && scheduleDay.getDate() == today.getDate();
        });
    },
    
    //昨日以前に達成した予定を格納するメソッド
    setBeforeClearSchedule(){
      if(!this.schedules){
        return;
      }
      let selectDay = new Date(this.selectDate);
      this.clearScheduleBefore = this.schedules.filter(schedule => {
          let scheduleDay = new Date(schedule.deleted_at);
          return scheduleDay.getFullYear() == selectDay.getFullYear() && scheduleDay.getMonth() == selectDay.getMonth();
        });
      this.clearScheduleBefore.sort(this.compareNumbers);
    },
    
    //昇順で達成済みタスクをソート
    compareDeletedAtUp(a, b) {
      return new Date(a.deleted_at).getTime() - new Date(b.deleted_at).getTime();
    },
    getClearMonth(data){
      let clear = new Date(data);
      return `${clear.getFullYear()}年${clear.getMonth()+1}月`;
    },
    async initializeData(){
      await this.getClearSchedule();
      this.setTodayClearSchedule();
      this.setBeforeClearSchedule();
      console.log(this.clearScheduleBefore);
    },
  },
  created(){
    //達成済み予定の取得
    this.initializeData();
    //達成済みタスクの取得
    //this.getClearData();
  },
  watch:{
      selectDate(){
        this.setBeforeClearSchedule();
      },
      
  }
}
</script>
<style>
  .body{
    padding:0; 
    margin:0;
    height: 100%;
  }
  .text {
    font-size: 14px;
  }
  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }

  /*.box-card {*/
  /*  width: 600px;*/
  /*}*/
</style>
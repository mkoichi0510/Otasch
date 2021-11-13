<template>
  <div　class="body">
    <div class="info" v-if="schedule">
      <p></p>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <h1>推奨予定：{{schedule.name}}</h1>
        </div>
        <div class="progress">
          
          <h2>
            <span>進捗率</span>
            <span　style="float: right" v-if="checkTerm(schedule) && checkDay">残り{{lemainDay(schedule)}}日</span>
            <span　style="float: right" v-else-if="checkTerm(schedule) && !checkDay">残り{{lemainDay(schedule)}}時間</span>
            <span　style="float: right" v-else>期限切れ</span>
          </h2>
          <el-progress :percentage="progress"></el-progress>
        </div>
       <h2 type="text">次にする推奨タスク:{{nextTaskName}}</h2>
        <el-button style="float: right; padding: 10px"type="primary" @click="MoveTask(schedule)">予定の調整</el-button>
        <br>
      </el-card>
      <p></p>
      <div class="todayList">
        <el-card class="box-card">
          <div slot="header" class="clearfix">
            <h1>
              <span>期限が迫っている予定一覧(残り{{limitDay}}日以内)</span>
              <el-dropdown>
                <span class="el-dropdown-link">
                  表示切替<i class="el-icon-arrow-down el-icon--right"></i>
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item @click.native="limitDay=1">残り1日以内</el-dropdown-item>
                  <el-dropdown-item @click.native="limitDay=5">残り5日以内</el-dropdown-item>
                  <el-dropdown-item @click.native="limitDay=10">残り10日以内</el-dropdown-item>
                  <el-dropdown-item @click.native="limitDay=30">残り30日以内</el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </h1>
          </div>
          <div class="limitSchedules" v-if="limitSchedules">
            <ul>
              <li v-for="(schedule, i) in paginateSchedulesLimit" :key="i">
              <!--<h2>達成日：{{getClearDate(schedule.deleted_at)}}</h2>-->
                <el-link type="primary"@click="MoveTask(schedule);">
                  <h2>{{ schedule.name }}</h2>
                <!--<h2>残り{{lemainDay(schedule)}}日</h2>-->
                <!--<h2 v-else>残り{{lemainDay(schedule)}}時間</h2>-->
                </el-link>
              </li>
            </ul>
            <el-pagination
              background
              layout="prev, pager, next"
              :current-page.sync="currentPageScheduleLimit"
              :page-size="perPageScheduleLimit"
              :total="limitSchedules.length">
            </el-pagination>
          </div>
        </el-card>
        <p></p>
      </div>
    </div>
    <div v-else class="todayList">
      <p></p>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <h1>推奨予定</h1>
        </div>
        <div class="task">
          <el-empty　description="NoData">
            <el-button type="primary"　@click="MoveSchedule">予定の追加</el-button>
            <h3>予定を追加して管理を始めよう</h3>
          </el-empty>
        </div>
      </el-card>
      <p></p>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <h1>期限が迫っている予定一覧</h1>
        </div>
        <div class="task">
          <el-empty　description="NoData">
          </el-empty>
        </div>
      </el-card>
      <p></p>
    </div>
  </div>
</template>

<script>
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'

export default {
  data () {
    return {
      schedule:null,
      schedules:null,
      limitSchedules:null,
      tasks: null,
      allTasks: null,
      clearTasks: null,
      day : 5,
      nextTaskName: "",
      nextScheduleName:"",
      checkDay:null,
      limitDay:10,
      //ぺジネーション用
      currentPageScheduleLimit : 1,//現在のページ 
      perPageScheduleLimit: 5, //1ページ毎の表示件数
    }
  },
  computed: {
    apiStatus () {
      return this.$store.state.data.apiStatus;
    },
    //予定の進捗率を算出するメソッド
    progress(){
      //達成済みタスク一覧とすべてのタスク一覧のデータがないときは処理を行わない
      if(!this.clearTasks || !this.allTasks){
        return;
      }
      //クリア済みタスクが1つ以上あるときのみ進捗率の計算を行う
      if(this.clearTasks.length > 0){
        return parseInt(this.clearTasks.length / this.allTasks.length * 100);
      }
      else{
        return 0;
      }
    },
    paginateSchedulesLimit() {
      //第1引数には取り出しの開始位置、第2引数には取り出しを終える直前の位置を渡す
      return this.limitSchedules.slice((this.currentPageScheduleLimit - 1) * this.perPageScheduleLimit, this.currentPageScheduleLimit * this.perPageScheduleLimit);
    },
    
  },
  methods:{
    //未完了予定の取得
    async getTodo(){
      await this.$store.dispatch('data/getSchedules');
      
      if(this.apiStatus){
        this.schedules = this.$store.state.data.schedules;
        this.taskLabel = "未達成";
      }
    },
    //未完了のタスクの取得
    async getData(){
      // console.log(this.schedule.id);
      await this.$store.dispatch('data/getTask', this.schedule.id);
      if(this.apiStatus){
        this.tasks = this.$store.state.data.tasks;
        this.taskLabel = "未達成";
      }
    },
    //達成済みを含めたすべてのタスクの取得
    async getAllData(){
      await this.$store.dispatch('data/getAllTask', this.schedule.id);
      if(this.apiStatus){
        this.allTasks = this.$store.state.data.tasks;
      }
    },
    //達成済みタスクのみ取得
    async getClearData(){
      await this.$store.dispatch('data/getClearTask', this.schedule.id);
      if(this.apiStatus){
        this.clearTasks = this.$store.state.data.tasks;
      }
    },
    //全データの初期設定
    async InitializeData(){
      //ログインユーザーの全未達成予定を取得
      await this.getTodo();
      //予定データがあるときのみ実行
      if(this.schedules.length > 0){
        //推奨予定の設定
        this.nextSchedule();
        this.getLimitScheduel();
        if(this.schedule){
          //推奨予定のタスクの更新
          this.updateTaskData();
        }
      }
    },
    //全タスク情報の更新
    async updateTaskData(){
      await this.getData();
      await this.nextTask();
      await this.getAllData();
      await this.getClearData();
    },
    
    //引数で与えられた予定の残り日数を算出するメソッド
    lemainDay(schedule){
      //期限と現在時刻をgetTime()でエポックミリ秒を取得して差分を出す
      let term = new Date(schedule.term);
      //期限の時刻を23時59分59秒に設定する。
      term.setHours(23,59,59);
      let diff = term.getTime() - Date.now();
      //日付けになるように、diffを割って変換。
      var day = diff/(1000*60*60*24);
      //残り日数が1日以上の場合は残り日数を返す
      if(parseInt(day) > 0){
        this.checkDay = true;
        return parseInt(day);
      }
      //残り日数が1日未満の場合は残り時間を返す
      else{
        this.checkDay = false;
        return parseInt(day*24);
      }
    },
    checkTerm(schedule){
      if(this.lemainDay(schedule) >= 0 && !schedule.deleted_at){
        return true;
      }
      else{
        return false;
      }
    },
    //次に取り組むべきタスクを算出するメソッド
    nextTask(){
      //予定が達成済みの場合
      if(this.schedule.deleted_at){
        this.nextTaskName = "";
        return;
      }
      
      //優先度の最大値を初期値として設定
      let pri = 10;
      //優先度が一番高いタスクの格納用変数
      let taskDatas = null;
      //優先度が一番高いタスクを格納
      while((!taskDatas || taskDatas.length == 0) && pri > 0){
        taskDatas = this.tasks.filter(task => {
          return task.priority == pri;
        });
        pri--;
      }
      if(taskDatas.length == 0){
        this.nextTaskName = "";
        return;
      }
      //優先度が一番高いタスクが重複したときはタスクの作成時間が一番若いものを選ぶ
      if(taskDatas.length > 1){
        let defaultTime = Date.now();
        taskDatas.forEach(task => {
          let createTime = new Date(task.created_at); 
          if(createTime.getTime() < defaultTime){
            defaultTime = createTime.getTime();
            this.nextTaskName = task.name;
          }
        });
      }
      else if(taskDatas.length == 1){
        this.nextTaskName = taskDatas[0].name;
      }
    },
    getLimitScheduel(){
      if(!this.schedules){
        return;
      }
      this.limitSchedules = this.schedules.filter(schedule => this.lemainDay(schedule) <= this.limitDay && this.checkTerm(schedule) && this.checkDay || !this.checkDay && this.checkTerm(schedule));
    },
    //次に取り組むべき予定を算出するメソッド
    nextSchedule(){
      //未達成予定がない場合
      if(!this.schedules){
        this.schedule = null;
        return;
      }else if(this.schedules.length == 1 && this.checkTerm(this.schedules[0]) && this.lemainDay(this.schedules[0])){
        this.schedule = this.schedules[0];
        return;
      }
      
      //残り日数÷優先度が一番低い予定を格納
      this.schedules.forEach(schedule => {
        //取り組むべき予定がある場合
        if(this.schedule && this.checkTerm(schedule) && this.checkDay){
          //新たに検討する予定の残り日数÷優先度の値
          let priValNew = this.lemainDay(schedule) / schedule.priority;
          if(!this.checkDay){
            priValNew = 0;
            console.log(schedule);
          }
          //現在最も低い残り日数÷優先度の値
          let priValNow = this.lemainDay(this.schedule) / this.schedule.priority;
          if(!this.checkDay){
            priValNow = 0;
          }
          //現在の残り日数÷優先度の値よりも低いときに更新
          if(priValNow > priValNew){
            this.schedule = schedule;
          }
          //現在の残り日数÷優先度の値が等しい場合は作成日が早い時に更新
          else if(priValNow == priValNew){
            let createTimeNow = new Date(this.schedule.created_at);
            let createTimeNew = new Date(schedule.created_at);
            //作成日が早いときに更新
            if(createTimeNow.getTime() > createTimeNew.getTime()){
              this.schedule = schedule;
            }
          }
        }
        //予定がnullの時かつ未達成かつ期限内の場合に設定
        else if(this.checkTerm(schedule) && this.lemainDay(schedule)){
        　console.log(schedule);
          this.schedule = schedule;
        }
      });

    },
    
    //タスク一覧へ移動
    MoveTask(schedule){
      this.$store.commit('data/setSchedule', schedule);
      this.$router.push("/task");
    },
    
    //予定一覧へ移動
    MoveSchedule(schedule){
      this.$router.push("/preview");
    },
  },
  created(){
    //予定およびタスクの初期設定
    this.InitializeData();
  },
  watch:{
      limitDay(){
        this.getLimitScheduel();
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
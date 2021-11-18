<template>
  <div class="body" v-loading.fullscreen.lock="loading">
    <div class="container--small" v-if="schedule">
      <createForm 
        :createFormVisible='createDialogVisible'
        @register-task="register" 
        @create-form-close="createDialogVisible=false"
      ></createForm>
      <detailForm 
        :detailFormVisible='detailDialogVisible'
        :detailData='detailData'
        :schedule='schedule'
        @delete-task="softDelete" 
        @update-task='updateTask'
        @force-delete='forceDelete'
        @detail-form-close="detailDialogVisible=false"
      ></detailForm>
      <div class="Body">
        <br>
        <el-button type="primary" icon="el-icon-arrow-left" @click="MovePreview">予定一覧へ</el-button>
        <el-row>
          <el-button v-if="checkScheduleState" type="primary"@click="createDialogVisible=true"　style="float: right">タスクの新規作成</el-button>
        </el-row>
        <div class="info">
          <p></p>
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <h1>{{schedule.name}}</h1>
            </div>
            <div class="progress">
              <h2>
                <span>進捗率</span>
                <span　style="float: right" v-if="checkTerm && checkDay">残り{{lemainDay}}日</span>
                <span　style="float: right" v-else-if="checkTerm && !checkDay">残り{{lemainDay}}時間</span>
                <span　style="float: right" v-else>期限切れ</span>
              </h2>
              <el-progress :percentage="progress"></el-progress>
            </div>
            <h2 type="text"　v-if="checkTerm">次にすること:{{nextTaskName}}</h2>
            <h2 type="text"　v-if="schedule.deleted_at">達成日:{{getClearDate()}}</h2>
            <br>
          </el-card>
          <p></p>
        </div>
        </div>
        <div class="taskList">
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <h1>
                <span>{{taskLabel}}タスク一覧</span>
                <el-dropdown>
                  <span class="el-dropdown-link">
                    表示切替<i class="el-icon-arrow-down el-icon--right"></i>
                  </span>
                  <el-dropdown-menu slot="dropdown">
                    <el-dropdown-item @click.native="tasks=clearTasks,taskLabel='達成済み'">達成済みタスク</el-dropdown-item>
                    <el-dropdown-item @click.native="getData(),taskLabel='未達成'">未達成タスク</el-dropdown-item>
                    <el-dropdown-item @click.native="tasks=allTasks, taskLabel='全'">全タスク</el-dropdown-item>
                  </el-dropdown-menu>
                </el-dropdown>
              </h1>
            </div>
            <div class="tasks"  v-if="tasks">
              <ul>
                <li v-for="(task, i) in paginateTasks" :key="i">
                  <el-link type="primary"@click="showDetail(task)"><h2 class='title'>{{ task.name }}</h2></el-link>
                </li>
              </ul>
              <div v-if="tasks.length < 1">
                <el-empty　description="NoData"></el-empty>
              </div>
              <el-pagination
                background
                layout="prev, pager, next"
                :current-page.sync="currentPage"
                :page-size="perPage"
                :total="tasks.length">
              </el-pagination>
            </div>
          </el-card>
          <p></p>
        </div>
      </div>
    </div>
</template>

<script>
import createForm from './TaskCreate.vue';
import detailForm from './TaskDetail.vue';
export default {
  name:'todoTask',
  components:{
    createForm,
    detailForm,
  },
  data(){
    return{
      schedule:null,　//画面に表示する予定を格納する変数
      tasks: null, //scheduleに結び付いているタスク一覧を格納する変数
      allTasks: null,　//scheduleに結び付いている全タスク一覧を格納する変数
      clearTasks: null, //scheduleに結び付いている達成済みタスクを格納する変数
      createDialogVisible: false, //新規タスク生成ダイアログの表示非表示を管理する変数
      detailDialogVisible: false, //選択したタスクの詳細ダイアログの表示非表示を管理する変数
      taskLabel:"未達成", //画面に表示するタスクの種類を表す文字列を格納する変数
      nextTaskName: "", //推奨タスク名を格納する変数
      checkDay:null, //残り日数が1日以上か1日未満かを判定する　true:1日以上　false:1日未満
      loading : false, //画面のローディング表示を管理する変数
      //タスク詳細ダイアログにpropsで渡すタスクデータ
      detailData: {
          name: '',//タスク名
          detail: '',//タスクの詳細
          priority: '',//タスクの優先度
          id:null, //タスクのid
          deleted_at: null, //タスクの達成日
      },
      
      //ペジネート用
      currentPage : 1,//現在のページ 
      perPage: 5, //1ページ毎の表示件数
      totalPage: 1, //総ページ数
    }
  },

  methods:{
    //予定一覧画面へ遷移
    MovePreview(){
      this.$router.push("/preview");
    },
    //未完了のタスクの取得
    async getData(){
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
    //次に取り組むべき予定を算出するメソッド
    nextTask(){
      //予定が達成済みの場合推奨タスクを表示せずに処理を終える
      if(this.schedule.deleted_at){
        this.nextTaskName = "";
        return;
      }
      //タスクがない場合推奨タスクを表示せずに処理を終える
      if(this.tasks.length == 0){
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
    //全タスク情報の更新
    async updateTaskData(){
      await this.getData();
      await this.getAllData();
      await this.getClearData();
      await this.nextTask();
      this.loading = false; //ローディング表示を解除
    },
    //新規タスクの取得
    async register(data){
      this.loading = true;//ローディング表示を入れる
      data.schedule_id = this.schedule.id;
      await this.$store.dispatch('data/registerTask', data);
      
      if (this.apiStatus) {
          //タスク作成後にタスク一覧を更新
          this.updateTaskData();
          this.createDialogVisible = false;
      }else{
        this.loading = false;//失敗時にローディング表示を消す
      }
    },
    //タスクの更新
    async updateTask(data){
      this.loading = true;
      data.schedule_id = this.schedule.id;
      await this.$store.dispatch('data/updateTask', data);
      
      if (this.apiStatus) {
        this.detailDialogVisible = false;
      }
      //タスク更新後にタスク一覧を更新
      this.updateTaskData();
    },
    //登録タスクの達成処理
    async softDelete(data){
      this.loading = true;
      data.schedule_id = this.schedule.id;
      await this.$store.dispatch('data/deleteTask', data);
      
      if (this.apiStatus) {
        //タスク完了処理後にタスク一覧を更新
        this.updateTaskData();
        this.detailDialogVisible = false;
      }else{
        this.loading = false;//失敗時にローディング表示を消す
      }
    },
    //タスクの取り消し
    async forceDelete(data){
      this.loading = true;
      data.schedule_id = this.schedule.id;
      await this.$store.dispatch('data/forceDeleteTask', data);
      
      if (this.apiStatus) {
        //タスク削除後にタスク一覧を更新
        this.updateTaskData();
        this.detailDialogVisible = false;
      }else{
        this.loading = false;//失敗時にローディング表示を消す
      }
    },
    
    //propsで受け渡すデータの設定とダイアログの表示
    showDetail(data){
      this.detailData = data;
      this.detailDialogVisible = true;
    },
    //scheduleに格納されている予定の達成日を年月日で返すメソッド
    getClearDate(){
      let clear = new Date(this.schedule.deleted_at);
      return `${clear.getFullYear()}年${clear.getMonth()+1}月${clear.getDate()}日`;
    },
  },
  computed: {
    //ペジネート用
    paginateTasks() {
        return this.tasks.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
    },
    //サーバー側に投げた処理が成功したかどうかを返す
    apiStatus () {
      return this.$store.state.data.apiStatus;
    },
    //scheduleに格納されている予定が期限切れまたは達成済みかどうかを判定しその結果を返すメソッド　true:期限内かつ未達成　false:期限切れまたは達成済み
    checkTerm(){
      if(this.lemainDay >= 0 && !this.schedule.deleted_at){
        return true;
      }
      else{
        return false;
      }
    },
    //scheduleに格納されている予定が達成済みかどうかを判定しその結果を返すメソッド　true:未達成　false:達成済み
    checkScheduleState(){
      if(!this.schedule.deleted_at){
        return true;
      }else{
        return false;
      }
    },
    //予定の残り日数を算出するメソッド
    lemainDay(){
      //期限と現在時刻をgetTime()でエポックミリ秒を取得して差分を出す
      let term = new Date(this.schedule.term);
      //期限時刻を23時59分59秒に設定する
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
    
  },
  created(){
    this.loading = true;//ローディング表示を入れる
    //dataストアにstateに設定されているscheduleをこのvueファイル内のschedule変数に格納
    this.schedule = this.$store.state.data.schedule;
    //現在のページを表す値の設定
    this.$store.commit('data/setPageIndex', "3");
    //dataストアのstateにscheduleのデータが入っていない場合には/previewに遷移させる
    if(!this.schedule){
      this.MovePreview();
    }
    else{
      //タスクデータの更新を行う
      this.updateTaskData();
    }
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
<template>
  <div class="body" v-loading.fullscreen.lock="loading">
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
            <span>{{ getMonth(selectDate) }}達成予定</span>
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
  </div>
</template>

<script>
export default {
  data () {
    return {
      schedules:null, //達成済み予定一覧格納用
      clearScheduleToday:null,　//当日の達成予定一覧格納用
      clearScheduleBefore:null, //特定月の達成予定一覧格納用
      selectDate:new Date(), //過去の完了済み予定でいつの完了済み予定を表示するのか指定するときに用いる
    　loading : false, //画面のローディング表示を管理する変数
      //ぺジネーション用
      currentPageSchedule : 1,//当日の達成済み予定の現在のページ 
      perPageSchedule: 5, //当日の達成済み予定の1ページ毎の表示件数
      currentPageScheduleBefore : 1,//すべての達成済み予定の現在のページ 
      perPageScheduleBefore: 5, //すべての達成済み予定の1ページ毎の表示件数
    }
  },
  computed: {
    //サーバー側に投げた処理が完了したかどうかを返す
    apiStatus () {
      return this.$store.state.data.apiStatus;
    },
    //当日の完了済み予定のペジネート用
    paginateSchedulesToday() {
      //第1引数には取り出しの開始位置、第2引数には取り出しを終える直前の位置を渡す
      return this.clearScheduleToday.slice((this.currentPageSchedule - 1) * this.perPageSchedule, this.currentPageSchedule * this.perPageSchedule);
    },
    //過去の完了済み予定のペジネート用
    paginateSchedulesBefore() {
      //第1引数には取り出しの開始位置、第2引数には取り出しを終える直前の位置を渡す
      return this.clearScheduleBefore.slice((this.currentPageScheduleBefore - 1) * this.perPageScheduleBefore, this.currentPageScheduleBefore * this.perPageScheduleBefore);
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
    
    //タスク一覧へ移動
    MoveTask(data){
      this.$store.commit('data/setSchedule', data);
      this.$router.push("/task");
    },
    //当日に達成した予定を格納するメソッド
    setTodayClearSchedule(){
      //達成済みタスクがない場合は処理を行わすに終了
      if(!this.schedules){
        return;
      }
      //当日の年月日と等しい予定を格納
      let today = new Date();
      this.clearScheduleToday = this.schedules.filter(schedule => {
          let scheduleDay = new Date(schedule.deleted_at);
          return scheduleDay.getFullYear() == today.getFullYear() && scheduleDay.getMonth() == today.getMonth() && scheduleDay.getDate() == today.getDate();
        });
    },
    
    //selectDateで指定した年月に達成した予定を格納するメソッド
    setBeforeClearSchedule(){
      //達成済みタスクがない場合は処理を行わすに終了
      if(!this.schedules){
        return;
      }
      //selectDateで指定した年月と等しい予定を格納
      let selectDay = new Date(this.selectDate);
      this.clearScheduleBefore = this.schedules.filter(schedule => {
          let scheduleDay = new Date(schedule.deleted_at);
          return scheduleDay.getFullYear() == selectDay.getFullYear() && scheduleDay.getMonth() == selectDay.getMonth();
        });
      //格納後に達成済み順で昇順にソート
      this.clearScheduleBefore.sort(this.compareDeletedAtUp);
    },
    
    //達成済み日でタスクを昇順にソート
    compareDeletedAtUp(a, b) {
      return new Date(a.deleted_at).getTime() - new Date(b.deleted_at).getTime();
    },
    //引数で渡された日付の年月を返すメソッド
    getMonth(data){
      let clear = new Date(data);
      return `${clear.getFullYear()}年${clear.getMonth()+1}月`;
    },
    //このvueファイルが読み込まれたときに実行するメソッド
    async initializeData(){
      await this.getClearSchedule();//達成済み予定の格納
      this.setTodayClearSchedule();//当日達成済み予定の格納
      this.setBeforeClearSchedule();//selectDateで指定した年月と等しい予定を格納
      this.loading = false;
    },
  },
  created(){
    this.loading = true;
    //達成済み予定の取得
    this.initializeData();
    //現在のページを表す値の設定
    this.$store.commit('data/setPageIndex', "4");
  },
  watch:{
     //selectDateの値が変わるたびに予定の格納を行う
      selectDate(){
        this.setBeforeClearSchedule();
      },
      
  }
}
</script>

<style>
  .clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }
</style>
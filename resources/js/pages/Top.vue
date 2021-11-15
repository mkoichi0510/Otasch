<template>
  <div　class="body">
    <div class="info">
      <p>
        <h2>このアプリは予定管理アプリです</h2>
        <h2>このイメージのように一目で見やすい予定管理ができます！</h2>
        <h2>早速ログインして予定の管理を始めましょう！</h2>
        <el-button type="primary"　@click="MoveLogin">ログイン/新規登録画面へ</el-button>
      </p>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <h1>推奨予定：課題</h1>
        </div>
        <div class="progress">
          
          <h2>
            <span>進捗率</span>
            <span　style="float: right">残り1日</span>
          </h2>
          <el-progress :percentage="20"></el-progress>
        </div>
       <h2 type="text">次にする推奨タスク:課題のアルゴリズムを考える</h2>
        <el-button style="float: right; padding: 10px"type="primary">予定の調整</el-button>
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
          <div class="limitSchedules">
            <ul>
              <li>
                <el-link type="primary">
                  <h2>課題</h2>
                </el-link>
              </li>
              <li>
                <el-link type="primary">
                  <h2>ゲーム制作</h2>
                </el-link>
              </li>
              <li>
                <el-link type="primary">
                  <h2>アプリ制作</h2>
                </el-link>
              </li>
            </ul>
          </div>
        </el-card>
        <p></p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      limitDay:10,　//期限が迫っている予定一覧で用いる
    }
  },
  methods:{
     //ログイン/登録画面へ移動
    MoveLogin(){
      this.$router.push("/login").catch(() => {});;
    },
  },
  created(){
    //現在のページを表す値の設定
    this.$store.commit('data/setPageIndex', "1");
  },
}
</script>
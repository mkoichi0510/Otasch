<template>
        
    <div v-if="todoListVisible" class="container--small">
      <div class="Body">
        <h1>予定一覧</h1>
        <el-button type="primary"@click="openSortDialog">Sort</el-button>
        <ul>
          <li v-for="(schedule, i) in paginateSchedules" :key="i"><el-link type="primary"@click="showDetail(schedule);"><h2 class='title'>{{ schedule.name }}</h2></el-link></li>
            <!--  <li v-for="schedule in schedules" :key="schedule.id"><RouterLink :to="{name:'schedule-url',params:{id:schedule}}"><h2 class='title'>{{ schedule.name }}</h2></RouterLink></li>-->
        </ul>
        <!--<paginate-links for="todo-list" class="pagination" :show-step-links="true"></paginate-links>-->
        <el-pagination
          background
          layout="prev, pager, next"
          :current-page.sync="currentPage"
          :page-size="perPage"
          :total="schedules.length">
        </el-pagination>
      </div>
      <div class="create" >
        <el-button type="primary"@click="changeCreateVisibleState(true)">Create</el-button>
      </div>
            <!--<div class='schedules'>-->
            <!--    @foreach ($schedules as $schedule)-->
            <!--        <div class='schedules'>-->
            <!--            <RouterLink class="button button--link" to="/home/{{$schedule->id}}"><h2 class='title'>{{ $schedule->name }}</h2></RouterLink>-->
            <!--        </div>-->
            <!--    @endforeach-->
            <!--</div>-->
            <!--<div class='paginate'>-->
            <!--    {{ $schedules->links()}}-->
            <!--</div>-->
    </div>
</template>
<script>
import VuePaginate from 'vue-paginate'
export default {
  name:'todoList',
  data(){
    return{
      schedules: null,
      paginate: ['todo-list'],
      todoListVisible: false,
      currentPage : 1,//現在のページ 
      perPage: 5, //1ページ毎の表示件数
      totalPage: 1, //総ページ数
      count: 1, //schedulesの総数
    }
  },

  props:{
      sortFormVisible:{
          type:Boolean,
          default:false,
      },
      todoLists:{
        type:Array,
        default:null
      },
      showDetail:{
          type:Function,
      },
      changeCreateVisibleState:{
          type:Function
      },
  },
  methods:{
    openSortDialog(){
      this.$emit('sort-form-open');
    }
  },
  computed: {
    paginateSchedules() {
      return this.schedules.slice((this.currentPage - 1) * this.perPage, this.currentPage * this.perPage);
    }
  },
  watch:{
    todoLists(newValue){
        this.schedules = newValue;
        this.totalPage = Math.ceil(this.schedules.length / this.perPage);
        this.count = this.schedules.length;
        this.todoListVisible = true;
      },
  }
}
</script>
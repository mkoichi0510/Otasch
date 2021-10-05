<!--<template>-->
<!--  <div class="container--small">-->
<!--      <h1>{{ $route.params.id.name }}</h1>-->
<!--      <el-button type="primary"@click="moveHome">予定の完了</el-button>-->
<!--      <h2>優先度:{{ $route.params.id.priority }}</h2>-->
<!--      <h2>期限:{{ $route.params.id.term }}</h2>-->
<!--      <h2>詳細:{{ $route.params.id.Text }}</h2>-->
<!--      <el-button type="primary"@click="moveHome">戻る</el-button>-->
<!--  </div>-->
<!--</template>-->
<template>
  <div class="container--small">
      <el-dialog 
        title="詳細" 
        :visible.sync="detailFormVisible" 
        width="70%"
        :before-close="handleClose"
        >
      <el-form ref="form" label-width="120px">
          <el-form-item label="予定名">
            <el-input　placeholder="{name}" v-model="input" :disabled="true"></el-input>
          </el-form-item>
          <el-form-item label="優先度">
            <h2>{{ priority }}</h2>
          </el-form-item>
          <el-form-item label="期限">
            <h2>{{ term }}</h2>
          </el-form-item>
          <el-form-item label ="詳細">
              <h2>{{ detail }}</h2>
            </el-form-item>
            <div class="form__button">
              <el-row>
                <el-button type="primary"@click="deleteSchedule">予定の完了</el-button>
              </el-row>
            </div>
        </el-form>
    </el-dialog>
  </div>
</template>
<!--<script>-->

<!--export default {-->

<!--  data(){-->
<!--    return{-->

<!--    }-->
<!--  },-->
<!--  methods:{-->
<!--      moveHome(){-->
<!--        this.$router.push("/home");-->
<!--    },-->
<!--    softDelete(){-->
      
<!--    }-->
<!--  },-->

<!--  mounted(){-->
<!--    console.log(this.$route.params.id.name)-->
<!--  }-->
<!--}-->
<!--</script>-->
<script>
export default {
  name:'editForm',

  props:{
      detailFormVisible:{
          type:Boolean,
          default:false,
      },
      // schedule:{
      //   type:Object,
      //   default:null
      // }
      name:{
        type:String,
        default:null
      },
      priority:{
        type:Number,
        default:null,
      },
      term:{
        type:String,
        default:null,
      },
      detail:{
        type:String,
        default:null,
      },
      id:{
        type:Number,
        default:null,
      }
  },
  methods:{
    handleClose() {
        this.$emit('edit-form-close');
        return
    },
    editSchedule(){
        //this.$emit('edit-schedule',this.id);
    },
    
  },
  computed: {
    apiStatus () {
      return this.$store.state.auth.apiStatus
    }
  },
//   watch:{
//       余裕があれば
//   }
}
</script>
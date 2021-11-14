<template>
  <div class="container--small">
      <el-dialog 
        title="" 
        :visible.sync="sortFormVisible" 
        width="70%"
        :before-close="handleClose"
        >
      <el-form ref="form" label-width="120px">
            <div class="sortType">
              <el-radio v-model="type" label="1">優先度</el-radio>
              <el-radio v-model="type" label="2">期限</el-radio>
            </div>
            <div class = "sortOrder">
              <el-radio v-model="order" label="1">昇順</el-radio></el-radio>
              <el-radio v-model="order" label="2">降順</el-radio>
              <el-row>
                <el-button type="primary"@click="sortSchedules(type,order)">実行</el-button>
              </el-row>
            </div>
        </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data () {
    return {
      type: '1',//ソートの種類を示す変数　1:優先度 2:期限
      order: '1', //ソートの順序を示す変数 1:昇順 2:降順
    }
  },
  props:{
    //ソートダイアログの表示非表示を管理する変数
    sortFormVisible:{
        type:Boolean,
        default:false,
    },
    //ソート処理を行うメソッド
    sortSchedules:{
      type:Function,
    }
  },
  methods:{
    //ダイアログを閉じる処理を親コンポーネントに投げる
    handleClose() {
        this.$emit('sort-form-close');
        return;
    },
  },
}
</script>


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
  name:'sortForm',
  data () {
      
    return {
      type: '1',
      order: '1',
    }
  },
  props:{
      sortFormVisible:{
          type:Boolean,
          default:false,
      },
      sortSchedules:{
        type:Function,
      }
  },
  methods:{
    handleClose() {
        this.$emit('sort-form-close');
        return
    },
  },
  computed: {
    apiStatus () {
      return this.$store.state.auth.apiStatus
    }
  },
  
}
</script>


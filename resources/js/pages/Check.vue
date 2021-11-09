<template>
  <div class="container--small">
      <el-dialog 
        title="確認画面" 
        :visible.sync="checkFormVisible" 
        width="30%"
        :before-close="handleClose"
        >
      <span>{{message}}</span>
      <span slot="footer" class="dialog-footer">
        <el-button @click="handleClose">キャンセル</el-button>
        <el-button type="primary" @click="confirm">続行</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name:'detailForm',

  props:{
      checkFormVisible:{
          type:Boolean,
          default:false,
      },
      message:{
        type:String,
        default:null
      },
  },
  methods:{
   handleClose() {
        this.$emit('check-form-close');
        return;
    },
    confirm(){
      this.$emit('confirm');
      this.handleClose();
    },
  },
}
</script>
<template>
  <div>
    <header>
      <Navbar />
    </header>
    <main>
      <div class="container">
       <RouterView /> 
      </div>
    </main>
    <Footer />
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
import { INTERNAL_SERVER_ERROR } from './util'

export default {
  components: {
    Navbar,
    Footer
  },
  computed: {
    errorCode () {
      return this.$store.state.error.code
    }
  },
  watch: {
    errorCode: {
      handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        }
      },
      immediate: true
    },
    $route () {
      this.$store.commit('error/setCode', null)
    }
  }
}
</script>
<style>
  .main{
    /*padding-top: 60px; */
    /*padding-bottom: 60px; */
    /*height: auto; */
    /*background-color: #fff000; */
    padding-bottom: 100px;
  }
  .Footer{
    position: fixed; /*ここを変更*/
    bottom: 0;
    width: 100%;
    height: 100px;
  }
  .Navbar{
    width: 100%;
    position: relative;
    height: auto !important;
    height: 100%;
    min-height: 100%;
  }
</style>
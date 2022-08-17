<template>
  <div id="main-container">
    <Header />
    <router-view />
    <Footer />
  </div>
</template>

<script>
import Footer from '@/layouts/components/Footer.vue'
import Header from '@/layouts/components/Header.vue'

export default {
  components: {
    Footer,
    Header
  }
}
</script>

<style lang="scss">
#main-container > div {
  min-width: 600px;
}
.container {
  min-height: calc(100vh - 100px);
  max-width: 100vw;
}
</style>

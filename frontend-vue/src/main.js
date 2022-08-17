import Vue from 'vue'
import App from './App.vue'

// Import Bootstrap
import '@/assets/scss/style.scss'

// Vuex
import Vuex from 'vuex'
Vue.use(Vuex)

// Vue Composition API
import VueCompositionAPI from '@vue/composition-api'
Vue.use(VueCompositionAPI)

// axios
import './axios.js'

// Vue Router
import router from './router'

// Vuex Store
import store from './store/store'

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app')

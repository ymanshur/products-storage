import Vue from 'vue'

// axios
import axios from 'axios'

const URL =  `${process.env.VUE_APP_BACKEND_URI}`;
const axiosIns = axios.create({
  // You can add your headers here
  // ================================
  baseURL: `${URL}`,
  // timeout: 1000,
  // headers: {'X-Custom-Header': 'foobar'}
})

Vue.prototype.$http = axiosIns

export default axiosIns

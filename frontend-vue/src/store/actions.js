import axios from '@/axios'

const actions = {
  fetchAllProduct({ commit }) {
    return new Promise((resolve, reject) => {
      axios.get('api')
        .then(response => {
          commit('SET_PRODUCT_LIST', response.data)
          resolve(response)
        })
        .catch(error => { reject(error.response) })
    })
  },
  addProduct(context, payload) {
    return new Promise((resolve, reject) => {
      axios.post('api', payload)
        .then(response => {
          resolve(response)
        })
        .catch(error => { reject(error.response) })
    })
  },
  deleteProduct(context, payload) {
    return new Promise((resolve, reject) => {
      axios.delete(`api?id=${payload}`)
        .then(response => {
          resolve(response)
        })
        .catch(error => { reject(error.response) })
    })
  }
}

export default actions

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '',
    component: () => import('@/layouts/main/Main.vue'),
    children: [
      {
        path: '/',
        name: 'product-list',
        component: () => import('@/views/ProductList.vue'),
        meta: {
          pageTitle: 'Product List'
        }
      },
      {
        path: '/add-product',
        alias: '/addproduct',
        name: 'product-add',
        component: () => import('@/views/ProductAdd.vue'),
        meta: {
          pageTitle: 'Product Add'
        }
      }
    ]
  }
]
const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior () {
    return { x: 0, y: 0 }
  },
  routes
})

export default router

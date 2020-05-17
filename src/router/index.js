import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    meta: {
      layout: 'MainLayout'
    },
    component: () => import('../views/Home.vue')
  },
  {
    path: '/FotoRecipes',
    name: 'FotoRecipes',
    meta: {
      layout: 'RecipeLayout',
      auth: true
    },
    component: () => import('../views/FotoRecipes.vue')
  },
  {
    path: '/Account/:id',
    name: 'Account',
    meta: {
      layout: 'MainLayout',
      auth: true
    },
    component: () => import('../views/Account.vue')
  },
  
  { path: '*', redirect: '/' }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.auth && !store.getters.getUserIsAuth) {
    // Нужно проверять залогинен пользователь, если нет выводить окно логина
    router.push("/"); // ?
    store.commit('setAuthForm', 1);
  } else {
    next()
  }
})

export default router

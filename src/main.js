import Vue from 'vue'
import App from './App.vue'
import store from './store'
import router from './router'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import MainLayout from '@/layouts/MainLayout.vue'
import RecipeLayout from '@/layouts/RecipeLayout.vue'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

// подключение слоев
Vue.component('MainLayout', MainLayout)
Vue.component('RecipeLayout', RecipeLayout)
Vue.component('Header', Header)
Vue.component('Footer', Footer)

Vue.config.productionTip = false

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

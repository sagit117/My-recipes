import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // app
    authForm: 0,        // состояние формы аутентификации 0 - нет, 1 - логин, 2 - забыли пароль, 3 - регистрация

    // user
    userIsAuth: false,  // состояние аутентификации пользователя
  },
  mutations: {
    setAuthForm(state, data) {
      state.authForm = data
    },
  },
  actions: {
  },
  getters: {
    getAuthForm: s => s.authForm, 
    getUserIsAuth: s => s.userIsAuth,
  },
  modules: {
  }
})

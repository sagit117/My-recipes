import Vue from 'vue'
import Vuex from 'vuex'
import API from '@/api/api'

const api = new API()

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // app
    authForm: 0,        // состояние формы аутентификации 0 - нет, 1 - логин, 2 - забыли пароль, 3 - регистрация

    // user
    userIsAuth: false,  // состояние аутентификации пользователя
    currentUser: {},    // данные пользователя

    // Alert
    alert: {            // данные окна alert
      show: false,
      title: "",
      text: "",
      state: 0
    },
  },
  mutations: {
    setAuthForm(state, data) {
      state.authForm = data
    },
    setUserAuth(state, data) {
      state.userIsAuth = data;
    },
    setCurrentUser(state, data) {
      state.currentUser = data;
    },
    setAlert(state, data) {
      state.alert = data;
    }
  },
  actions: {
    loginWithPass({ commit }, data) { // логинем пользователя по паролю
      return new Promise((resolve, reject) => {
        api.loginUserWitchPass(data)    
        .then((res) => {
          if (res.errorCode === '') {
            commit('setUserAuth', true);
            commit('setCurrentUser', res.user);
            // установим данные для логина по хеш
            localStorage.setItem('userLogin', res.user.post);
            localStorage.setItem('userHash', res.hash);
            resolve();
          } else if (res.errorCode === 'auth/hash_wrong') {
            commit('setAlert', { show: true, title: "Ошибка", text: res.errorText, state: 2 })
            reject();
          } else {
            reject(res); 
          }
        })
        .catch((error) => {
          console.log(error);
          commit('setAlert', { show: true, title: "Ошибка", text: error, state: 2 })
          reject();
        })
      })
    }
  },
  getters: {
    getAuthForm: s => s.authForm, 
    getUserIsAuth: s => s.userIsAuth,
    getAlert: s => s.alert,
  },
  modules: {
  }
})

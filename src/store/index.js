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

    // Wait
    showWait: false,    //  состояние загрузки
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
    },
    setShowWait(state, data) {
      state.showWait = data;
    }
  },
  actions: {
    loginWithPass({ commit }, data) { // логинем пользователя по паролю
      return new Promise((resolve, reject) => {
        api.loginUserWithPass(data)    
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
    },
    loginWithHash({ commit }, data) { // логинем по хеш
      commit('setShowWait', true);
      api.loginUserWithHash(data.login, data.hash)
      .then((res) => {
        commit('setShowWait', false);
        if (res.errorCode === '') {
          commit('setUserAuth', true);
          commit('setCurrentUser', res.user);
        } 
      })
      .catch((error) => {
        commit('setShowWait', false);
        console.log(error);
      })
    },
    resetPass({ commit }, data) {     // восстановление пароля через форму
      return new Promise((resolve, reject) => {
        commit('setShowWait', true);
        api.resetUserPass(data)
        .then((res) => {
          commit('setShowWait', false);
          if (res.errorCode === '') {
            resolve();
            commit('setAlert', 
              { show: true, 
                title: "Успешно", 
                text: "Новый пароль отправлен на указанный email, так же проверьте папку СПАМ!", 
                state: 1 })
          } else if (res.errorCode === 'send_new_pass/no_send_email') {
            commit('setAlert', { show: true, title: "Ошибка", text: res.errorText, state: 1 })
            resolve();
          } else {
            reject();
          }
        })
        .catch((error) => {
          console.log(error);
          commit('setShowWait', false);
          commit('setAlert', { show: true, title: "Ошибка", text: error, state: 2 })
          reject();
        })
      })
    },
    hasUser({ commit }, data) {       // проверяет существует ли пользователь по email
      commit('setShowWait', true);
      return new Promise((resolve, reject) => {
        api.hasUser(data.post)
        .then((res) => {
          resolve(res)
          commit('setShowWait', false);
        })
        .catch((error) => {
          console.log(error);
          commit('setShowWait', false);
          commit('setAlert', { show: true, title: "Ошибка", text: error, state: 2 });
          reject();
        })
      })
    },
    sendCodeReg({ commit }, data) {   // выслать код регистрации
      commit('setShowWait', true);
      return new Promise((resolve, reject) => {
        api.sendCodeReg(data)
        .then((res) => {
          commit('setShowWait', false);
          resolve(res);
          if (res.errorCode === '') {
            commit('setAlert', { 
              show: true, 
              title: "Успешно",
              text: "Проверочный код отправлен на указанный email, так же проверьте папку СПАМ!",
              state: 1
            })
          } 
        })
        .catch((error) => {
          console.log(error);
          commit('setShowWait', false);
          commit('setAlert', { show: true, title: "Ошибка", text: error, state: 2 });
          reject();
        })
      })
    }
  },
  getters: {
    getAuthForm: s => s.authForm, 
    getUserIsAuth: s => s.userIsAuth,
    getAlert: s => s.alert,
    getShowWait: s => s.showWait,
    getCurrentUser: s => s.currentUser
  },
  modules: {
  }
})

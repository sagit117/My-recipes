import axios from "axios"

const apiMyRecipes = "https://www.axel-dev.ru.com/api2/";

class API {
  loginUserWithPass(data) {
    return axios.post(`${apiMyRecipes}c_login.php`, data)
    .then((response) => {
      return response.data
    })
  }

  loginUserWithHash(login, hash) {
    return axios.get(`${apiMyRecipes}c_login.php?login=${login}&hash=${hash}`)
    .then((response) => {
      return response.data
    })
  }

  resetUserPass(login) {
    return axios.get(`${apiMyRecipes}c_sendNewPass.php?login=${login}`)
    .then((response) => {
      return response.data
    })
  }

  hasUser(post) {
    return axios.get(`${apiMyRecipes}c_hasUser.php?post=${post}`)
    .then((response) => {
      return response.data
    })
  }

  sendCodeReg(email) {
    return axios.get(`${apiMyRecipes}c_sendCode.php?email=${email}`)
    .then((response) => {
      return response.data
    })
  }

  regUser(email, pass) {
    return axios.get(`${apiMyRecipes}c_reg.php?email=${email}&pass=${pass}`)
    .then((response) => {
      return response.data
    })
  }

  getUser(data) {
    return axios.post(`${apiMyRecipes}c_getUser.php`, data)
    .then((response) => {
      return response.data
    })
  }

  updateUser(data) {
    return axios.post(`${apiMyRecipes}c_updateUser.php`, data)
    .then((response) => {
      return response.data
    })
  }

 }

export default API
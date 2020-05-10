import axios from "axios"

const apiMyRecipes = "https://www.axel-dev.ru.com/api2/";

class API {
  loginUserWitchPass(data) {
    return new Promise((resolve, reject) => {
      axios.post(`${apiMyRecipes}c_login.php`, data)
      .then((response) => {
        resolve(response.data);
      })
      .catch((error) => {
        reject(error);
      })
    })
  }

}

export default API
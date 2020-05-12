<template>
  <div class="cover shadow-sm p-3 mb-5 bg-white rounded" style="min-width: 340px"> <!-- login -->
    <div class="container">

      <button type="button" class="close pointer zindex-10" aria-label="Close" @click="$store.commit('setAuthForm', 0)"> 
        <span aria-hidden="true">&times;</span>
      </button>

      <div class="row">
        <div class="col">

          <form novalidate @submit.prevent="submit"> <!-- class="was-validated" -->

            <Input 
              type="text" 
              @on-input="emailInput" 
              :isError="emailError.minLength || emailError.wrongEmail || emailError.wrongPass"
              :textError="errorTextEmail"
              caption="Email:"
            />

            <Input 
              type="password" 
              @on-input="passInput" 
              caption="Пароль:"
            />

            <div class="form-group text-center">
              <a href="#" class="d-inline" @click.prevent="$store.commit('setAuthForm', 2)"> Забыли пароль? </a> | 
              <a href="#" class="d-inline" @click.prevent="$store.commit('setAuthForm', 3)"> Регистрация </a>
            </div>
              
            <button class="btn btn-primary" type="button" disabled v-if="showWait">
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Загрузка...
            </button>
            <button type="submit" class="btn btn-primary" v-else>
              Войти
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
  // component: Login
  // version: 0.0.1
  // date: 05.2020
  // sagit117@gmail.com 

import Input from '@/components/Input.vue'
import {minLength, testEmail} from '@/utils/Validate.js'

export default {
  components: {
    Input
  },
  data() {
    return {
      email: "",
      emailError: {
        minLength: false,
        wrongEmail: false,
        wrongPass: false
      },
      pass: "",
      showWait: false
    }
  },
  computed: {
    errorTextEmail() {
      let error = "";
      if (this.emailError.minLength) error = "Поле email не должно быть пустым!"
      if (this.emailError.wrongPass) error = "Не верный логин или пароль!"
      if (this.emailError.wrongEmail) error = "Введите корректный email"
      return error;
    }
  },
  methods: {
    emailInput(value) {
      this.emailError.minLength = minLength(value);
      this.emailError.wrongPass = false;
      this.emailError.wrongEmail = false;
      this.email = value;
    },
    passInput(value) {
      this.emailError.wrongPass = false;
      this.pass = value;
    },
    validateEmail() {
      for (let key in this.emailError) {
        if (this.emailError[key]) return true;
      }
      return false;
    },
    submit() {
      this.emailError.minLength = minLength(this.email);
      this.emailError.wrongEmail = !testEmail(this.email);

      if (!this.validateEmail()) {
        this.showWait = true;
        const formData = new FormData();
        formData.append('login', this.email);
        formData.append('pass', this.pass);

        this.$store.dispatch('loginWithPass', formData)
        .then(() => {
          this.showWait = false;
          this.$store.commit('setAuthForm', 0);
        })
        .catch((e) => {
          this.showWait = false;
          if (e.errorCode === 'auth/pass_wrong') this.emailError.wrongPass = true;
        })
      }
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
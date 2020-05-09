<template>
  <div class="cover shadow-sm p-3 mb-5 bg-white rounded" style="min-width: 340px"> <!-- login -->
    <div class="container">

      <button type="button" class="close pointer zindex-10" aria-label="Close" @click="$store.commit('setAuthForm', 0)"> 
        <span aria-hidden="true">&times;</span>
      </button>

      <div class="row">
        <div class="col">

          <form novalidate> <!-- class="was-validated" -->

            <Input 
              type="text" 
              @on-input="emailInput" 
              :isError="emailError.minLength"
              :textError="errorTextEmail"
            />

            <Input 
              type="password" 
              @on-input="passInput" 
            />

            <!--<div class="form-group">
              <label for="pass">Пароль:</label>
              <input 
                type="password" 
                class="form-control" 
                id="pass" 
                v-model.trim="pass">
            </div>-->


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
import Input from '@/components/Input.vue'
import {minLength} from '@/utils/Validate.js'

export default {
  components: {
    Input
  },
  data() {
    return {
      email: "",
      emailError: {
        minLength: false,
        noEmail: false,
        wrongEmail: false
      },
      pass: "",
      showWait: false
    }
  },
  computed: {
    errorTextEmail() {
      let error = "";
      if (this.emailError.minLength) error = "Поле email не должно быть пустым!"

      return error;
    }
  },
  methods: {
    emailInput(value) {
      this.emailError.minLength = minLength(value);
      this.email = value;
    },
    passInput(value) {
      this.pass = value;
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
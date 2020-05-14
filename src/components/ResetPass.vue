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
              :isError="errorTextEmail !== ''"
              :textError="errorTextEmail"
              caption="Email:"
            />

            <div class="form-group text-center">
              <a href="#" class="d-inline" @click.prevent="$store.commit('setAuthForm', 1)"> Войти </a> | 
              <a href="#" class="d-inline" @click.prevent="$store.commit('setAuthForm', 3)"> Регистрация </a>
            </div>
              
            <button class="btn btn-primary" type="button" disabled v-if="showWait">
              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              Загрузка...
            </button>
            <button type="submit" class="btn btn-primary" v-else>
              Отправить
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
  // component: ResetPass
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
        noEmail: false
      },
      showWait: false
    }
  },
  computed: {
    errorTextEmail() {
      let error = "";
      if (this.emailError.minLength) error = "Поле email не должно быть пустым!"
      if (this.emailError.wrongEmail) error = "Пользователя не существует!"
      if (this.emailError.noEmail) error = "Введите корректный email"
      return error;
    }
  },
  methods: {
    emailInput(value) {
      this.emailError.minLength = minLength(value);
      this.emailError.wrongEmail = false;
      this.emailError.noEmail = false;
      this.email = value;
    },
    invalidEmail() {
      for (let key in this.emailError) {
        if (this.emailError[key]) return true;
      }
      return false;
    },
    submit() {
      this.emailError.minLength = minLength(this.email);
      this.emailError.noEmail = !testEmail(this.email);

      if (!this.invalidEmail()) {
        this.showWait = true;
        this.$store.dispatch('resetPass', this.email)
        .then((res) => {
          this.showWait = false;
          if (res.errorCode === '') this.$store.commit('setAuthForm', 1);
          this.emailError.noEmail = res.errorCode === 'send_new_pass/no_send_email';
          this.emailError.wrongEmail = res.errorCode === 'send_new_pass/email_wrong';
        })
        .catch(() => {
          this.showWait = false;
        })
      }
    }
  }
}
</script>
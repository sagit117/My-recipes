<template>
  <div class="cover shadow-sm p-3 mb-5 bg-white rounded" style="min-width: 340px"> <!-- registr -->
    <div class="container">

      <button type="button" class="close pointer" aria-label="Close" @click="$store.commit('setAuthForm', 0)"> 
        <span aria-hidden="true">&times;</span>
      </button>

      <div class="row">
        <div class="col">

          <form @submit.prevent="submit" novalidate> <!-- class="was-validated" -->
            <Input 
              type="text" 
              @on-input="emailInput" 
              :isError="errorTextEmail !== ''"
              :textError="errorTextEmail"
              caption="Email:"
              :maxLength="64"
              :success="emailTrue"
            />

            <Input 
              type="password" 
              @on-input="passInput" 
              caption="Пароль:"
              :maxLength="32"
              :textError="errorTextPass"
              :isError="errorTextPass !== ''"
            />

            <Input 
              type="password" 
              @on-input="confInput" 
              caption="Подтверждение пароля:"
              :success="confPassTrue"
            />

            <div class="form-group">
              <button class="btn btn-info" type="button" disabled v-if="showTimeOut">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  повторить через {{ timeOutSendCode }} сек.
              </button>
              <button type="button" class="btn btn-info" @click="sendCode" v-else>Выслать код</button>
            </div>

            <Input 
              type="text" 
              @on-input="codeInput" 
              :textError="errorTextCode"
              :isError="errorTextCode !== ''"
              caption="Проверочный код:"
            />

            <div class="form-group text-center">
              <a href="#" class="d-inline" @click.prevent="$store.commit('setAuthForm', 1)"> Войти </a> | 
              <a href="#" class="d-inline" @click.prevent="$store.commit('setAuthForm', 2)"> Забыли пароль? </a>
            </div>
            <button type="submit" class="btn btn-primary">Регистрация</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
  // component: Registration
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
        issetEmail: false
      },
      pass: "",             // пароль пользователя
      passError: {
        minLength: false,
        wrongPass: false,
      },
      confPass: "",
      code: "",
      resCode: "",
      resCodePost: "",
      codeError: {
        minLength: false,
        wrongCode: false,
        wrongEmail: false
      },
      showWait: false,
      showTimeOut: false,
      timeOutSendCode: 30,
      emailTrue: false,
      confPassTrue: false   // подтверждение совпадает
    }
  },
  computed: {
    errorTextEmail() {
      let error = "";
      if (this.emailError.minLength) error = "Поле email не должно быть пустым!"
      if (this.emailError.issetEmail) error = "Пользователь существует!"
      if (this.emailError.wrongEmail) error = "Введите корректный email"
      return error;
    },
    errorTextPass() {
      let error = "";
      if (this.passError.minLength) error = "Поле пароль не должно быть пустым!"
      if (this.passError.wrongPass) error = "Пароль и подтверждение не совпадают!"
      return error;
    },
    errorTextCode() {
      let error = "";
      if (this.codeError.wrongEmail) error = "Почта не совпадает с почтой на которую был выслан код!";
      if (this.codeError.minLength) error = "Поле проверочный код не должно быть пустым!";
      if (this.codeError.wrongCode) error = "Код не совпадает!";
      
      return error;
    }
  },
  methods: {
    emailInput(value) {
      this.emailError.minLength = minLength(value);
      this.emailError.issetEmail = false;
      this.emailError.wrongEmail = false;
      this.emailTrue = false;
      this.email = value;
    },
    passInput(value) {
      this.passError.minLength = minLength(value);
      this.pass = value;
      this.confPassTrue = (this.confPass === this.pass && !this.passError.minLength);
      this.passError.wrongPass = false;
    },
    confInput(value) {
      this.confPass = value;
      this.confPassTrue = (this.confPass === this.pass && !this.passError.minLength);
      this.passError.wrongPass = false;
    },
    codeInput(value) {
      this.codeError.minLength = minLength(value);
      this.code = value;
    },
    regAcept() {
      if (this.errorTextPass !== '' || this.errorTextCode !== '' || this.errorTextEmail !== '') return false;
      return true;
    },
    sendCode() {
      this.emailError.wrongEmail = !testEmail(this.email);

      if (!this.emailError.wrongEmail) {
        this.$store.dispatch('hasUser', { post: this.email })
        .then((res) => {
          this.emailError.issetEmail = (res === 0) ? false : true
          if (!this.emailError.issetEmail) {
            this.$store.dispatch('sendCodeReg', this.email)
            .then((res) => {
              if (res.errorCode === '') {
                // успех
                this.resCode = res.code;
                this.resCodePost = res.post;
                this.emailTrue = true;

                this.showTimeOut = true;
                const interval = setInterval(()=> {
                  this.showTimeOut = true;
                  this.timeOutSendCode--;

                  if (this.timeOutSendCode === 0) {
                    clearInterval(interval);
                    this.timeOutSendCode = 30;
                    this.showTimeOut = false;
                  }
                }, 1000);
              } else {
                this.emailError.issetEmail = res.errorTextCode === 'send_code/isset_email'
                this.emailError.wrongEmail = res.errorTextCode === 'send_code/email_wrong'
              }
            })
            .catch(() => {})
          }
        })
      }
    },
    submit() {
      this.emailError.wrongEmail = !testEmail(this.email);                                   // email корректный и не пуст
      this.codeError.wrongEmail = this.resCodePost !== this.email && this.resCodePost !== '';// email равен email на который отправлен код
      this.codeError.wrongCode = this.code !== this.resCode;                                 // проверочный коды совпадают
      this.codeError.minLength = minLength(this.code);                                       // проверочный код не пуст
      this.passError.minLength = minLength(this.pass);                                       // пароль не пуст
      this.passError.wrongPass = this.confPass !== this.pass                                 // пароли совпадают

      if (this.regAcept()) {
        // регистрация
        this.showWait = true;
        this.$store.dispatch('regUser', { post: this.email, pass: this.pass })
        .then((res) => {
          this.showWait = false;
          if (res.errorCode === '') {
            this.$store.commit('setAlert', { show: true, 
              title: "Успешно", 
              text: "Вы зарегистрированы!", 
              state: 1 })
            this.$store.commit('setAuthForm', 1)
          }
          this.emailError.wrongEmai = res.errorTextCode === 'reg/email_wrong';
          this.emailError.issetEmail = res.errorTextCode === 'reg/email_isset'
        })
        .catch(() => {
          this.showWait = false;
        })
      }
    },
  }
}
</script>
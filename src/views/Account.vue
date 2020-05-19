<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        <h3>Личный кабинет пользователя {{ email }}</h3>
      </div>
      <div class="col">
        <button 
          type="button" 
          class="btn btn-info" 
          @click="confirm = { show: true, title: 'Подтвердите', text: 'Вы уверены, что хотите выйти из аккаунта?'}">
            Выйти из аккаунта
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <form novalidate @submit.prevent="submitName">
          <Input 
            type="text" 
            @on-input="inputName" 
            :isError="nameError.minLength || nameError.symbolWrong"
            :textError="errorTextName"
            :maxLength="32"
            caption="Имя:"
            ref="name"
          />

          <Input 
            type="text" 
            @on-input="inputSurame" 
            :isError="surnameError.symbolWrong"
            :textError="errorTextSurname"
            :maxLength="32"
            caption="Фамилия:"
            ref="surname"
          />

          <Input 
            type="text" 
            @on-input="inputPatron" 
            :isError="patronError.symbolWrong"
            :textError="errorTextPatron"
            :maxLength="32"
            caption="Отчество:"
            ref="patron"
          />

          <button type="submit" class="btn btn-success mb-2">Сохранить</button>

        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <form novalidate @submit.prevent="submitPass">
          <Input 
            type="password" 
            @on-input="inputOldPass" 
            :isError="oldPassError.minLength || oldPassError.wrongOldPass"
            :textError="errorTextOldPass"
            :maxLength="32"
            caption="Старый пароль:"
          />

          <Input 
            type="password" 
            @on-input="inputPass" 
            :isError="passError.minLength"
            :textError="errorTextPass"
            :maxLength="32"
            caption="Новый пароль:"
          />

          <Input 
            type="password" 
            @on-input="inputConfPass" 
            :isError="confPassError.wrongConfPass"
            :textError="errorTextConfPass"
            :maxLength="32"
            caption="Подтверждение пароля:"
            :success="confPass === pass && pass.length > 0"
          />

          <button type="submit" class="btn btn-success mb-2">Изменить пароль</button>

        </form>
      </div>
    </div>

    <Confirm 
      v-if="confirm.show" 
      :title="confirm.title" 
      :text="confirm.text" 
      @click-ok="exit"
      @click-cancel="confirm.show = false"
    />
  </div>
</template>

<script>
import Input from '@/components/Input.vue'
import Confirm from '@/components/Confirm.vue'
import {minLength, testSymbol} from '@/utils/Validate.js'

export default {
  components: {
    Input,
    Confirm
  },
  data() {
    return {
      email: "",
      name: "",
      nameError: {
        minLength: false,
        symbolWrong: false
      },
      surname: "",
      surnameError: {
        symbolWrong: false
      },
      patron: "",
      patronError: {
        symbolWrong: false
      },
      confirm: {
        show: false,
        text: "",
        title: ""
      },
      oldPass: "",
      oldPassError: {
        minLength: false,
        wrongOldPass: false
      },
      pass: "",
      passError: {
        minLength: false,
      },
      confPass: "",
      confPassError: {
        wrongConfPass: false
      },
    }
  },
  mounted() {
    if (this.$route.params.id > 0) {
      this.$store.dispatch('getUser', this.$route.params.id)
      .then((res) => {
        this.email = res.post;
        this.name = res.name;
        this.$refs.name.inputData = this.name;
        this.surname = res.surname;
        this.$refs.surname.inputData = this.surname;
        this.patron = res.patronymic;
        this.$refs.patron.inputData = this.patron;
      })
      .catch(() => {})
    } else {
      this.$router.push("/");
    }
  },
  computed: {
    errorTextName() {
      let error = "";
      if (this.nameError.symbolWrong) error = "Можно использовать только буквенные символы!";
      if (this.nameError.minLength) error = "Поле имя не должно быть пустым!";
      return error;
    },
    errorTextSurname() {
      let error = "";
      if (this.surnameError.symbolWrong) error = "Можно использовать только буквенные символы!";
      return error;
    },
    errorTextPatron() {
      let error = "";
      if (this.patronError.symbolWrong) error = "Можно использовать только буквенные символы!";
      return error;
    },
    errorTextOldPass() {
      let error = "";
      if (this.oldPassError.wrongOldPass) error = "Старый пароль не совпадает!";
      if (this.oldPassError.minLength) error = "Поле старый пароль не должно быть пустым!";
      return error;
    },
    errorTextPass() {
      let error = "";
      if (this.passError.minLength) error = "Поле пароль не должно быть пустым!";
      return error;
    },
     errorTextConfPass() {
      let error = "";
      if (this.confPassError.wrongConfPass) error = "Подтверждение и пароль не совпадает!";
      return error;
    },

  },
  methods: {
    submitName() {
      this.nameError.minLength = minLength(this.name);
      this.nameError.symbolWrong = !testSymbol(this.name);
      this.surnameError.symbolWrong = (!testSymbol(this.surname) && !minLength(this.surname));
      this.patronError.symbolWrong = (!testSymbol(this.patron) && !minLength(this.patron));

      if (!this.nameError.minLength && !this.nameError.symbolWrong && !this.surnameError.symbolWrong && !this.patronError.symbolWrong) {
        // submit
        let formData = new FormData();
        formData.append("id", this.$route.params.id);
        formData.append("name", this.name);
        formData.append("surname", this.surname);
        formData.append("patronymic", this.patron);

        this.$store.dispatch("updateUser", formData);
      }

    },
    submitPass() {
      this.passError.minLength = minLength(this.pass);
      this.oldPassError.minLength = minLength(this.oldPass);
      this.confPassError.wrongConfPass = this.pass !== this.confPass;

      if (!this.passError.minLength && !this.oldPassError.minLength && !this.confPassError.wrongConfPass) {
        // submit pass
        let formData = new FormData();
        formData.append("id", this.$route.params.id);
        formData.append("oldPass", this.oldPass);
        formData.append("newPass", this.pass);

        this.$store.dispatch("updateUser", formData);
      }
    },
    inputName(value) {
      this.name = value;
      this.nameError.minLength = minLength(this.name);
      this.nameError.symbolWrong = !testSymbol(this.name);
    },
    inputSurame(value) {
      this.surname = value;
      this.surnameError.symbolWrong = (!testSymbol(this.surname) && !minLength(this.surname));
    },
    inputPatron(value) {
      this.patron = value;
      this.patronError.symbolWrong = (!testSymbol(this.patron) && !minLength(this.patron));
    },
    inputOldPass(value) {
      this.oldPass = value;
      this.oldPassError.minLength = minLength(this.oldPass);
    },
    inputPass(value) {
      this.pass = value;
      this.passError.minLength = minLength(this.pass);
    },
    inputConfPass(value) {
      this.confPass = value;
      this.confPassError.wrongConfPass = false;
    },
    exit() {
      localStorage.removeItem("userHash");
      localStorage.removeItem("userLogin");
      this.$store.commit('setUserAuth', false);
      this.$store.commit('setCurrentUser', {});
      this.$router.push('/');
    }
  }
}
</script>
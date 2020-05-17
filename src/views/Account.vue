<template>
  <div class="container-fluid">
    <h3>Личный кабинет пользователя {{ email }}</h3>

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

        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Input from '@/components/Input.vue'
import {minLength, testSymbol} from '@/utils/Validate.js'

export default {
  components: {
    Input
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
      }
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
  },
  methods: {
    submitName() {
      //
    },
    inputName(value) {
      this.name = value;
      this.nameError.minLength = minLength(this.name);
      this.nameError.symbolWrong = !testSymbol(this.name);
    },
    inputSurame(value) {
      this.surname = value;
      this.surnameError.symbolWrong = (!testSymbol(this.surname) && !minLength(this.surname));
    }
  }
}
</script>
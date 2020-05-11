<template>
  <div class="form-group">
    <label for="email">{{ caption }}</label>
    <input 
      :type="type" 
      class="form-control" 
      v-model.trim="inputData"
      :class="{ 'is-invalid' : isError }"
      @input="onInput">
    <small class="form-text text-danger" >{{ textError }}</small>
  </div>
</template>

<script>
  // component: Input
  // version: 0.0.1
  // date: 05.2020
  // sagit117@gmail.com 
export default {
  props: {
    type: String,
    isError: Boolean,
    textError: String,
    caption: String
  },
  data() {
    return {
      inputData: ''
    }
  },
  methods: {
    onInput() {
      this.$emit("on-input", this.inputData);
    }
  }
}
</script>
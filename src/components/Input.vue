<template>
  <div class="form-group">
    <label for="email">Email:</label>
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
export default {
  props: {
    type: String,
    isError: Boolean,
    textError: String
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
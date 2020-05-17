<template>
  <div class="form-group">
    <label for="email">{{ caption }}</label>
    <input 
      :type="type" 
      class="form-control" 
      v-model.trim="inputData"
      :class="{ 'is-invalid' : isError && !success, 'is-valid': success && !isError }"
      @input="onInput">
    <small class="form-text text-danger" v-if="isError">{{ textError }}</small>
    <small class="form-text text-info" v-else-if="maxLength > 0">{{ inputData.length }} / {{ maxLength }}</small>
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
    caption: String,
    maxLength: Number,
    success: Boolean,
  },
  data() {
    return {
      inputData: '',
    }
  },
  watch: {
    value: (v) => {
      if (this.inputData !== undefined) console.log(v)
    },
  },
  methods: {
    onInput() {
      if (this.maxLength > 0 && this.inputData.length > this.maxLength) this.inputData = this.inputData.slice(0, this.maxLength)
      this.$emit("on-input", this.inputData);
    }
  }
}
</script>
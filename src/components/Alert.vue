<template>
  <div 
    class="alert cover" 
    style="min-width: 340px" 
    role="alert" 
    :class="{ 'alert-success': state === 1, 'alert-danger': state === 2 }">
    
    <h4 class="alert-heading">{{ title }}</h4>
    <hr>
    <p class="mb-0">{{ text }}</p>
    <button type="button" class="close" aria-label="Close" @click="close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</template>

<script>
  // component: Alert
  // version: 0.0.1
  // date: 05.2020
  // sagit117@gmail.com 

export default {
  data() {
    return {
      title: this.$store.getters.getAlert.title,
      text: this.$store.getters.getAlert.text,
      state: this.$store.getters.getAlert.state
    }
  },
  methods: {
    close() {
      this.$store.commit('setAlert', { show: false, title: "", text: "", state: 0 });
    },
  }

}
</script>

<style lang="scss" scoped>
  .cover {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
  }
</style>>
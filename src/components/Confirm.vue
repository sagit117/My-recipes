<template>
  <div 
    class="alert cover alert-warning" 
    style="min-width: 340px" 
    role="alert">
    
    <h4 class="alert-heading">{{ title }}</h4>
    <hr>
    <p class="mb-0">{{ text }}</p>
    <button type="button" class="btn btn-success m-2" @click="$emit('click-ok')">ОК</button>
    <button type="button" class="btn btn-danger m-2" @click="$emit('click-cancel')">Отмена</button>

  </div>
</template>

<script>
  // component: Confirm
  // version: 0.0.1
  // date: 05.2020
  // sagit117@gmail.com 
  
export default {
  props: {
    title: String,
    text: String
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
<template>
  <div id="app">
    <component :is="layout">
      <router-view :key="$route.path" ></router-view>
    </component>

    <Login v-if="$store.getters.getAuthForm === 1" />
  </div>
</template>

<script>
import Login from '@/components/Login.vue'

export default {
  components: {
    Login
  },
  data() {
    return {
      transitionName: ''
    }
  },
  computed: {
    layout() {
      return this.$route.meta.layout || 'MainLayout'
    }
  }
}
</script>

<style lang="scss">
  .pointer {
    cursor: pointer;
  }
  .cover {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%,-50%);
    z-index: 10;
  }
</style>

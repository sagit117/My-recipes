<template>
  <div id="app">
    <component :is="layout">
      <router-view :key="$route.path" ></router-view>
    </component>

    <Login v-if="$store.getters.getAuthForm === 1" />
    <ResetPass v-if="$store.getters.getAuthForm === 2" />
    <Registration v-if="$store.getters.getAuthForm === 3" />
    <Alert v-if="$store.getters.getAlert.show" />
    <Wait v-if="$store.getters.getShowWait" />
  </div>
</template>

<script>
import Login from '@/components/Login.vue'
import ResetPass from '@/components/ResetPass.vue'
import Registration from '@/components/Registration.vue'
import Alert from '@/components/Alert.vue'
import Wait from '@/components/Wait.vue'

export default {
  components: {
    Login,
    ResetPass,
    Registration,
    Alert,
    Wait
  },
  data() {
    return {
    }
  },
  computed: {
    layout() {
      return this.$route.meta.layout || 'MainLayout'
    }
  },
  created() {
    this.$store.dispatch('loginWithHash', { hash: localStorage.getItem('userHash'), login: localStorage.getItem('userLogin') });
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
  .close {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 20;
  }
</style>

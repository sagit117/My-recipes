<template>
  <div id="app">
    <Header />

    <div class="container">
      
      <slot></slot>

      <Footer />
    </div>
  </div>
</template>

<script>
export default {
  name: 'MainLayout'
}
</script>
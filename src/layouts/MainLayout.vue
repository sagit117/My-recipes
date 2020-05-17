<template>
  <div id="app">
    <Header />

    <div class="container-fluid">
      
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
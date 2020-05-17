<template>
  <div>
    
    <Header />

    <div class="nav-scroller py-1 mb-2">
      <nav class="nav d-flex justify-content-end">
        <router-link class="p-2 text-dark" to="FotoRecipes">Мои фото рецепты</router-link>
      </nav>
    </div>

    <div class="container-fluid">
      
      <slot></slot>

      <Footer />
      
    </div>
  </div>
</template>

<script>

export default {
  name: 'RecipeLayout',
}
</script>
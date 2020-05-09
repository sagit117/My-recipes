<template>
  <div id="nav">
    <div class="d-flex flex-column flex-sm-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
      <router-link to="/" tag="h5" class="my-0 mr-sm-auto font-weight-normal pointer">My Recipes</router-link>
      <nav class="my-2 my-md-0 mr-md-3">
        <router-link class="p-2 text-dark" to="/FotoRecipes">Мои рецепты</router-link>
      </nav>
      <button class="btn btn-primary" @click="$store.commit('setAuthForm', 1)">Логин</button>
    </div>
  </div>
</template>

<script>
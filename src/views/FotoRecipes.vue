<template>
  <div class="container-fluid">
    <div class="row align-item-start justify-content-start">
      <div class="dropdown mr-2"> <!-- menu filter -->
        <button 
          class="btn btn-secondary dropdown-toggle" 
          type="button" 
          id="dropdownMenuButton" 
          data-toggle="dropdown" 
          aria-haspopup="true" 
          aria-expanded="false">
          Фильтры
        </button>
        <div class="dropdown-menu" >
          <h5 class="dropdown-item">Настройка фильтров</h5>
          <div class="dropdown-divider"></div>
          <div class="form-check dropdown-item" @click.stop="">
            <input class="form-check-input" type="checkbox" value="" id="diet">
            <label class="form-check-label" for="diet">
              Только диетические
            </label>
          </div>
          <div class="form-check dropdown-item" @click.stop="">
            <input class="form-check-input" type="checkbox" value="" id="fav">
            <label class="form-check-label" for="fav">
              Только избранные
            </label>
          </div>
          <div class="dropdown-item p-2">
            <select class="custom-select custom-select-sm dropdown-item" @click.stop="">
              <option value="-1">Все</option>
              <option value="0">Без категории</option>
              <option v-for="group in groupsFR" :key="group.id">{{ group.name }}</option>
            </select>
          </div>
        </div>
      </div>

      <button type="button" class="btn btn-primary align-self-start">Новый рецепт</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      groupsFR: {},
    }
  },
  mounted() {
    let formData = new FormData();
    formData.append('userID', this.$store.getters.getCurrentUser.id);

    this.$store.dispatch('getGroupsFR', formData)
    .then((res) => {
      this.groupsFR = res
    })
    .catch(() => {})
  },
  methods: {
  }
}
</script>
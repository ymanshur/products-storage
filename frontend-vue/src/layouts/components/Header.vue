<template>
  <div
    id="header-app"
    class="sticky-top px-3 bg-white text-dark"
  >
    <div class="d-flex bd-highlight align-items-center p-2">     
      <h3 class="me-auto bd-highlight align-self-center my-0">
        {{ pageTitle }}
      </h3>
      <div v-show="linkName === 'product-list'">
        <button
          type="button"
          class="btn btn-primary mx-1"
          @click="goToProductAdd"
        >
          ADD
        </button>
        <button
          type="button"
          class="btn btn-danger"
          @click="deleteData"
        >
          MASS DELETE
        </button>        
      </div>
      <div v-show="linkName === 'product-add'">
        <button
          type="submit"
          class="btn btn-primary mx-1"
          form="product_form"
        >
          Save
        </button>
        <button
          type="button"
          class="btn btn-danger"
          @click="goToProductList"
        >
          Cancel
        </button>
      </div>
    </div>
    <hr class="m-0">
  </div>
</template>

<script>
export default {
  name: 'header-web-app',
  computed: {
    pageTitle () {
        return this.$route.meta.pageTitle;
    },
    linkName () {
        return this.$route.name;
    },
    deleteId () {
      return this.$store.state.product_id
    }
  },
  methods: {
    goToProductAdd () {
      this.$router.push({ name: 'product-add'})
    },
    goToProductList () {
      this.$router.push({ name: 'product-list'})
    },
    refreshData () {
      this.$store.dispatch('fetchAllProduct')
    },
    deleteData() {
      this.$store.dispatch('deleteProduct', this.deleteId.join(","))      
        .then(() => {
          this.refreshData()
        })
    }
  }
}
</script>
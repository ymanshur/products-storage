<template>
  <div class="container">
    <div class="d-flex flex-wrap">
      <div
        class="card m-2 product-card"
        v-for="(value, index) in allProduct"
        :key="index"
      >
        <div class="card-body">
          <input
            class="delete-checkbox"
            style="cursor: pointer;"
            type="checkbox"
            :value="value.id"
            v-model="checkedProduct"
          >
          <div class="text-center">
            <h5 class="card-title">
              {{ value.product_sku }}
            </h5>
            <p>
              {{ value.product_name }}
              <br>
              {{ value.product_price }} $
              <br>
              {{ unit[value.product_type].sizeName }}: {{ measure(value) }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      checkedProduct: [],
      unit: {
        DVD: {
          unit: ' MB',
          sizeName: 'Size',
          data: ['product_size']
        },
        Book: {
          unit: 'KG',
          sizeName: 'Weight',
          data: ['product_weight']
        },
        Furniture: {
          unit: '',
          sizeName: 'Dimension',
          data: [
            'product_height',
            'product_width',
            'product_length'
          ]
        },
      }
    }
  },
  computed: {
    allProduct() {
      return this.$store.state.product_list
    }
  },
  watch: {
    checkedProduct() {
      this.$store.commit('SET_DELETE_ID', this.checkedProduct)
    }
  },
  methods: {
    measure(value) {
      let measurementValue = ''
      this.unit[value.product_type].data.forEach((element, index) => {
        if (index+1 == this.unit[value.product_type].data.length) measurementValue += value[element]
        else measurementValue += `${value[element]}X`
      });
      return `${measurementValue}${this.unit[value.product_type].unit}`
    }
  },
  created () {
    this.$store.dispatch('fetchAllProduct')
  }  
}
</script>

<style lang="scss">
.product-card {
  max-width: 250px;
  min-width: 230px;
  flex: 1 1 0px!important;
}
</style>
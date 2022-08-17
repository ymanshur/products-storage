<template>
  <div class="container">
    <ValidationObserver v-slot="{ handleSubmit }">
      <form
        id="product_form"
        class="p-3"
        @submit.prevent="handleSubmit(onSubmit)"
      >
        <div class="row mb-3">
          <label
            for="sku"
            class="col-sm-2 col-form-label"
          >
            SKU
          </label>
          <div class="col-sm-10">
            <ValidationProvider
              name="SKU"
              rules="required|notWhitespace"
              v-slot="{ errors }"
            >
              <input
                id="sku"
                class="form-control"
                type="text"
                v-model="skuValue"
              >
              <span class="text-muted">
                {{ errors[0] }}
              </span>
            </ValidationProvider>
          </div>
        </div>
        <div class="row mb-3">
          <label
            for="name"
            class="col-sm-2 col-form-label"
          >
            Name
          </label>
          <div class="col-sm-10">
            <ValidationProvider
              name="Name"
              rules="required"
              v-slot="{ errors }"
            >
              <input
                id="name"
                class="form-control"
                type="text"
                v-model="nameValue"
              >
              <span class="text-muted">
                {{ errors[0] }}
              </span>
            </ValidationProvider>
          </div>
        </div>
        <div class="row mb-3">
          <label
            for="price"
            class="col-sm-2 col-form-label"
          >
            Price ($)
          </label>
          <div class="col-sm-10">
            <ValidationProvider
              name="Price"
              rules="required"
              v-slot="{ errors }"
            >
              <input
                id="price"
                class="form-control"
                type="number"
                v-model="priceValue"
              >
              <span class="text-muted">
                {{ errors[0] }}
              </span>
            </ValidationProvider>
          </div>
        </div>
        <div class="row mb-3">
          <label
            class="col-sm-2 col-form-label"
            for="productType"
          >
            Type Switcher
          </label>
          <div class="col-sm-10">
            <ValidationProvider
              name="Product Type"
              rules="select"
              v-slot="{ errors }"
            >
              <select
                id="productType"
                class="form-select"
                v-model="selectValue"
              >
                <option
                  selected
                  value="Select"
                >
                  Type Switcher
                </option>
                <option
                  id="DVD"
                  value="DVD"
                >
                  DVD-disc
                </option>
                <option
                  id="Book"
                  value="Book"
                >
                  Book
                </option>
                <option
                  id="Furniture"
                  value="Furniture"
                >
                  Furniture
                </option>
              </select>
              <span class="text-muted">
                {{ errors[0] }}
              </span>
            </ValidationProvider>
          </div>
        </div>
        <div
          class="row mb-3"
          v-for="(value, index) in productTypeForm[selectValue].form"
          :key="index"
        >
          <label
            :for="value.id"
            class="col-sm-2 col-form-label"
          >
            {{ value.label }}
          </label>
          <div class="col-sm-10">            
            <ValidationProvider
              :name="value.label"
              rules="required|notZero" 
              v-slot="{ errors }"
            >
              <input
                :id="value.id"
                class="form-control"
                type="number"
                v-model="formData[value.variable]"
              >
              <span class="text-muted">
                {{ errors[0] }}
              </span>
            </ValidationProvider>
          </div>
        </div>
        <p class="fw-bolder">
          {{ productTypeForm[selectValue].description }}
        </p>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { extend, ValidationObserver , ValidationProvider } from 'vee-validate';
import { required } from 'vee-validate/dist/rules';

extend('required', {
  ...required,
  message: 'Please, submit the {_field_} in this field'
});

extend('select', value => {
  if (value != 'Select') {
    return true;
  }

  return 'Please, select the {_field_} in this field';
});

extend('notZero', value => {
  if (value > 0) {
    return true;
  }

  return 'Please, provide a value greater than zero to {_field_} data';
});

extend('notWhitespace', value => {  
  const validate = /\s/.test(value)
  if (!validate) {
    return true;
  }

  return 'Please, provide {_field_} data without whitespace';
});

export default {
  data () {
    return {
      nameValue: '',
      selectValue: 'Select',
      skuValue: '',
      priceValue: 0,
      formData: {        
        selectSize: 0,
        selectHeight: 0,
        selectWidth: 0,
        selectLength: 0,
        selectWeight: 0,
      },
      productTypeForm: {
        Select: {
          form: [],
          description: ''
        },
        DVD: {
          form: [
            {
              variable: 'selectSize',
              label: 'Size (MB)',
              id: 'size'
            }
          ],
          description: 'Please, provide disc space in MB'
        },
        Furniture: {
          form: [
            {
              variable: 'selectHeight',
              label: 'Height (CM)',
              id: 'height'
            },
            {
              variable: 'selectWidth',
              label: 'Width (CM)',
              id: 'width'
            },
            {
              variable: 'selectLength',
              label: 'Length (CM)',
              id: 'length'
            }
          ],
          description: 'Please, provide dimensions in HxWxL format'
        },
        Book: {
          form: [
            {
              variable: 'selectWeight',
              label: 'Weight (KG)',
              id: 'weight'
            }
          ],
          description: 'Please, provide weight in KG'
        },
      }
    }
  },
  components: {
    ValidationObserver,
    ValidationProvider
  },
  watch: {
    selectValue () {
      Object.keys(this.formData).map((element) => {
        this.formData[element]= 0
      })
    },
    skuValue () {
      this.skuValue = this.skuValue.trim()
    }
  },
  methods: {    
    onSubmit () {
      let parameterBody = {
        product_sku: this.skuValue,
        product_name: this.nameValue,
        product_price: this.priceValue,
        product_type: this.selectValue,
        product_size: this.formData['selectSize'],
        product_weight: this.formData['selectWeight'],
        product_height: this.formData['selectHeight'],
        product_width: this.formData['selectWidth'],
        product_length: this.formData['selectLength']
      }
      this.$store.dispatch('addProduct', parameterBody)  
        .then(() => {
          this.$router.push({ name: 'product-list' })
        })
        .catch(() => {
          this.$router.go(0)
        })
    }
  }
}
</script>

<style lang="scss">
.form-control {
  width: 300px;
}

.form-select {
  width: 300px;
}
</style>

export default function main() {
    const baseURL = "http://localhost:8000/restapi/products"

    let allProducts = []
    let selectedProduct = null

    const resetForm = () => {
        document.getElementById("formProduct").reset()
    }

    const fetchProducts = () => {
        fetch(baseURL)
            .then(response => {
                return response.json()
            })
            .then(responseJson => {
                allProducts = responseJson
                renderAllProducts(responseJson)
            })
    }


    const insertProduct = (product) => {
        fetch(baseURL, {
            method: "POST",
            body: JSON.stringify(product)
        })
            .then(() => {
                resetForm()
                fetchProducts()
            })
    }

    const removeProduct = (productId) => {
        fetch(`${baseURL}/${productId}`, {
            method: "DELETE",
        })
            .then(() => {
                fetchProducts()
            })
    }

    const updateProduct = (product) => {
        fetch(`${baseURL}/${product.id}`, {
            method: "PUT",
            body: JSON.stringify(product)
        })
            .then(() => {
                resetForm()
                fetchProducts()
            })
    }

    const handleProductDimension = (productType) => {
        const productSize = document.querySelector("#productSize")
        const productWeight = document.querySelector("#productWeight")
        const productHeight = document.querySelector("#productHeight")
        const productWidth = document.querySelector("#productWidth")
        const productLength = document.querySelector("#productLength")

        switch (productType) {
            case 'book':
                productSize.hidden = true
                productWeight.hidden = false
                productHeight.hidden = true
                productWidth.hidden = true
                productLength.hidden = true
                break;
            case 'dvd':
                productSize.hidden = false
                productWeight.hidden = true
                productHeight.hidden = true
                productWidth.hidden = true
                productLength.hidden = true
                break;
            case 'furniture':
                productSize.hidden = true
                productWeight.hidden = true
                productHeight.hidden = false
                productWidth.hidden = false
                productLength.hidden = false
                break;
            default:
                break;
        }
    }
        
    const setSelectedProduct = (productId) => {
        const product = allProducts.find(product => product.id == productId)

        const inputProductSKU = document.querySelector("#inputProductSKU")
        inputProductSKU.value = product.product_sku

        const inputProductName = document.querySelector("#inputProductName")
        inputProductName.value = product.product_name

        const inputProductPrice = document.querySelector("#inputProductPrice")
        inputProductPrice.value = product.product_price

        const inputProductType = document.querySelector("#inputProductType")
        inputProductType.value = product.product_type

        handleProductDimension(inputProductType.value)

        const inputProductSize = document.querySelector("#inputProductSize")
        inputProductSize.value = product.product_size

        const inputProductWeight = document.querySelector("#inputProductWeight")
        inputProductWeight.value = product.product_weight

        const inputProductHeight = document.querySelector("#inputProductHeight")
        inputProductHeight.value = product.product_height

        const inputProductWidth = document.querySelector("#inputProductWidth")
        inputProductWidth.value = product.product_width

        const inputProductLength = document.querySelector("#inputProductLength")
        inputProductLength.value = product.product_length

        selectedProduct = product
    }


    const renderAllProducts = (products) => {
        const listProductElement = document.querySelector("#listProduct")
        listProductElement.innerHTML = ""

        products.forEach(product => {
            listProductElement.innerHTML += `
                <div class="col-lg-4 col-md-6 col-sm-12" style="margin-top:12px">
                    <div class="card">
                        <div class="card-body">
                            <h5>(${product.product_sku}) ${product.product_name}</h5>
                            <p class="text-uppercase">${product.product_type}</p>
                            <p>$${product.product_price}</p>
                            <button type="button" class="btn btn-danger btn-delete" id="${product.id}">Delete</button>
                            <button type="button" class="btn btn-primary btn-edit" id="${product.id}">Edit</button>
                        </div>
                    </div>
                </div>
            `
        })

        const deleteButtons = document.querySelectorAll(".btn-delete")
        deleteButtons.forEach(button => {
            button.addEventListener("click", event => {
                const productId = event.target.id
                removeProduct(productId)
            })
        })

        const editButtons = document.querySelectorAll(".btn-edit")
        editButtons.forEach(button => {
            button.addEventListener("click", event => {
                const productId = event.target.id
                setSelectedProduct(productId)
            })
        })
    }

    document.addEventListener("DOMContentLoaded", () => {

        const inputProductSKU = document.querySelector("#inputProductSKU")
        const inputProductName = document.querySelector("#inputProductName")
        const inputProductPrice = document.querySelector("#inputProductPrice")
        const inputProductType = document.querySelector("#inputProductType")
        const inputProductSize = document.querySelector("#inputProductSize")
        const inputProductWeight = document.querySelector("#inputProductWeight")
        const inputProductHeight = document.querySelector("#inputProductHeight")
        const inputProductWidth = document.querySelector("#inputProductWidth")
        const inputProductLength = document.querySelector("#inputProductLength")

        const buttonCreate = document.querySelector("#buttonCreate")
        const buttonUpdate = document.querySelector("#buttonUpdate")

        inputProductType.addEventListener("change", e => {
            handleProductDimension(inputProductType.value)
        })

        buttonCreate.addEventListener("click", function () {
            const product = {
                product_sku: inputProductSKU.value,
                product_name: inputProductName.value,
                product_price: inputProductPrice.value,
                product_type: inputProductType.value,
            }
            switch (inputProductType.value) {
                case 'book':
                    product.product_size = inputProductSize.value
                    product.product_weight = null
                    product.product_height = null
                    product.product_width = null
                    product.product_length = null
                case 'dvd':
                    product.product_size = null
                    product.product_weight = inputProductWeight.value
                    product.product_height = null
                    product.product_width = null
                    product.product_length = null
                    break;
                case 'furniture':
                    product.product_size = null
                    product.product_weight = null
                    product.product_height = inputProductHeight.value
                    product.product_width = inputProductWidth.value
                    product.product_length = inputProductLength.value
                    break;
                default:
                    break;
            }

            insertProduct(product)
        })

        buttonUpdate.addEventListener("click", function () {
            selectedProduct = {
                ...selectedProduct,
                product_sku: inputProductSKU.value,
                product_name: inputProductName.value,
                product_price: inputProductPrice.value,
                product_type: inputProductType.value,
            }
            switch (inputProductType.value) {
                case 'book':
                    selectedProduct.product_size = inputProductSize.value
                    selectedProduct.product_weight = null
                    selectedProduct.product_height = null
                    selectedProduct.product_width = null
                    selectedProduct.product_length = null
                case 'dvd':
                    selectedProduct.product_size = null
                    selectedProduct.product_weight = inputProductWeight.value
                    selectedProduct.product_height = null
                    selectedProduct.product_width = null
                    selectedProduct.product_length = null
                    break;
                case 'furniture':
                    selectedProduct.product_size = null
                    selectedProduct.product_weight = null
                    selectedProduct.product_height = inputProductHeight.value
                    selectedProduct.product_width = inputProductWidth.value
                    selectedProduct.product_length = inputProductLength.value
                    break;
                default:
                    break;
            }

            updateProduct(selectedProduct)
        })

        fetchProducts()
    })
}
function main() {
    const baseUrl = "https://php-webapp.widyaanalytic.com/restapi/api";
    let allProducts = [];
    let selectedProduct = null;

    const resetForm = () => {
        document.getElementById("formProduct").reset();
    }
    const getProduct = () => {
        fetch(`${baseUrl}`)
        .then(response => {
            return response.json();
        })
        .then(responseJson => {
            // const responseJson = JSON.parse(this.responseText);
            if(responseJson.error) {
                showResponseMessage(responseJson.message);
            } else {
                allProducts = responseJson
                renderAllProducts(responseJson);
            }
        })
        .catch(error => {
            showResponseMessage(error);
        })
    }


    const insertProduct = (product) => {
        fetch(`${baseUrl}`, {
            method: "POST",
            body: JSON.stringify(product)
        })
        .then(response => {
            // console.log(JSON.stringify(product))
            return response.json();
        })
        .then(responseJson => {
            showResponseMessage(responseJson.message);
            resetForm();
            getProduct();
        })
        .catch(error => {
            showResponseMessage(error);
        })
    };

    const removeProduct = (productId) => {
        fetch(`${baseUrl}?id=${productId}`, {
            method: "DELETE",
        })
        .then(response => {
            return response.json();
        })
        .then(responseJson => {
            showResponseMessage(responseJson.message);
            getProduct();
        })
        .catch(error => {
            showResponseMessage(error);
        })
    };

    const updateProduct = (product) => {
        fetch(`${baseUrl}`, {
            method: "PUT",
            body: JSON.stringify(product)
        })
        .then(response => {
            console.log(JSON.stringify(product))
            return response.json();
        })
        .then(responseJson => {
            showResponseMessage(responseJson.message);
            resetForm();
            getProduct();
        })
        .catch(error => {
            showResponseMessage(error);
        })
    };
        
    const getDetailProductById = (productId) => {
        const arrayProducts = allProducts;
        const productData = arrayProducts.find(product => product.id === productId);

        const inputProductSKU = document.querySelector("#inputProductSKU");
        inputProductSKU.value = productData.product_sku

        const inputProductName = document.querySelector("#inputProductName");
        inputProductName.value = productData.product_name

        const inputProductPrice = document.querySelector("#inputProductPrice");
        inputProductPrice.value = productData.product_price

        const inputProductType = document.querySelector("#inputProductType");
        inputProductType.value = productData.product_type

        selectedProduct = productData
    };

    

    /*
        jangan ubah kode di bawah ini ya!
    */

    const renderAllProducts = (products) => {
        const listProductElement = document.querySelector("#listProduct");
        listProductElement.innerHTML = "";

        products.forEach(product => {
            listProductElement.innerHTML += `
                <div class="col-lg-4 col-md-6 col-sm-12" style="margin-top: 12px;">
                    <div class="card">
                        <div class="card-body">
                            <h5>(${product.product_sku}) ${product.product_name}</h5>
                            <p>${product.product_type}</p>
                            <p>$${product.product_price}</p>
                            <button type="button" class="btn btn-danger button-delete" id="${product.id}">Delete</button>
                            <button type="button" class="btn btn-primary button-edit" id="${product.id}">Edit</button>
                        </div>
                    </div>
                </div>
            `;
        });

        const deleteButtons = document.querySelectorAll(".button-delete");
        deleteButtons.forEach(button => {
            button.addEventListener("click", event => {
                const productId = event.target.id;
                removeProduct(productId);
            })
        })

        const editButtons = document.querySelectorAll(".button-edit");
        editButtons.forEach(button => {
            button.addEventListener("click", event => {
                const productId = event.target.id;
                getDetailProductById(productId);
            })
        })
    };

    const showResponseMessage = (message = "Check your internet connection") => {
        alert(message);
    };

    document.addEventListener("DOMContentLoaded", () => {

        const inputProductSKU = document.querySelector("#inputProductSKU");
        const inputProductName = document.querySelector("#inputProductName");
        const inputProductPrice = document.querySelector("#inputProductPrice");
        const inputProductType = document.querySelector("#inputProductType");
        // const inputProductSize = document.querySelector("#inputProductSize");
        const inputProductSize = 10;
        const buttonCreate = document.querySelector("#buttonCreate");
        const buttonUpdate = document.querySelector("#buttonUpdate");

        buttonCreate.addEventListener("click", function () {
            const product = {
                product_sku: inputProductSKU.value,
                product_name: inputProductName.value,
                product_price: inputProductPrice.value,
                product_type: inputProductType.value,
                product_size: inputProductSize,
                product_weight: inputProductSize
            };
            insertProduct(product);
        });

        buttonUpdate.addEventListener("click", function () {
            // console.log(selectedProduct);
            
            // const book = {
            //     id: Number.parseInt(inputBookId.value),
            //     title: inputBookTitle.value,
            //     author: inputBookAuthor.value
            // };

            updateProduct(selectedProduct);
        });

        getProduct();
    });
}

export default main;
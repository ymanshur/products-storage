function main() {
    const baseUrl = "https://php-webapp.widyaanalytic.com/restapi/api";
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
            console.log(JSON.stringify(product))
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
                            <button type="button" class="btn btn-danger button-delete" id="${product.id}">Hapus</button>
                            <button type="button" class="btn btn-primary button-edit" id="${product.id}">Ubah</button>
                        </div>
                    </div>
                </div>
            `;
        });

        const buttons = document.querySelectorAll(".button-delete");
        buttons.forEach(button => {
            button.addEventListener("click", event => {
                const productId = event.target.id;
                removeProduct(productId);
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
        const inputProductSize = document.querySelector("#inputProductSize");
        const buttonSave = document.querySelector("#buttonSave");
        // const buttonUpdate = document.querySelector("#buttonUpdate");

        buttonSave.addEventListener("click", function () {
            const product = {
                // id: Number.parseInt(inputBookId.value),
                product_sku: inputProductSKU.value,
                product_name: inputProductName.value,
                product_price: inputProductPrice.value,
                product_type: inputProductType.value,
                product_size: inputProductSize.value,
            };
            insertProduct(product)
        });

        // buttonUpdate.addEventListener("click", function () {
        //     const book = {
        //         id: Number.parseInt(inputBookId.value),
        //         title: inputBookTitle.value,
        //         author: inputBookAuthor.value
        //     };

        //     updateBook(book)
        // });
        getProduct();
    });
}

export default main;
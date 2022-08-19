<?php
namespace Src\Controllers;

use Src\Services\{ProductService};

class ProductController
{
    private $db;
    private $requestMethod;
    private $productId;

    private $productService;

    public function __construct($db, $requestMethod, $productId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->productId = $productId;

        $this->productService = new ProductService($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case "GET":
                if ($this->productId) {
                    $response = $this->getProduct($this->productId);
                } else {
                    $response = $this->getAllProducts();
                };
                break;
            case "POST":
                $response = $this->createProductFromRequest();
                break;
            case "PUT":
                $response = $this->updateProductFromRequest($this->productId);
                break;
            case "DELETE":
                $response = $this->deleteProduct($this->productId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response["status_code_header"]);
        if ($response["body"]) {
            echo $response["body"];
        }
    }

    private function getAllProducts()
    {
        $result = $this->productService->findAll();
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode($result);
        return $response;
    }

    private function getProduct($id)
    {
        $result = $this->productService->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = json_encode($result);
        return $response;
    }

    private function createProductFromRequest()
    {
        $input = (array) json_decode(file_get_contents("php://input"), TRUE);
        if (! $this->validateProduct($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->productService->insert($input);
        $response["status_code_header"] = "HTTP/1.1 201 Created";
        $response["body"] = null;
        return $response;
    }

    private function updateProductFromRequest($id)
    {
        $result = $this->productService->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents("php://input"), TRUE);
        // if (! $this->validateProduct($input)) {
        //     return $this->unprocessableEntityResponse();
        // }
        if (! $this->validateProductSKU($input["product_sku"])) {
            return $this->unprocessableEntityResponse();
        }
        $this->productService->update($id, $input);
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = null;
        return $response;
    }

    private function deleteProduct($id)
    {
        $result = $this->productService->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->productService->delete($id);
        $response["status_code_header"] = "HTTP/1.1 200 OK";
        $response["body"] = null;
        return $response;
    }

    private function validateProduct($input)
    {
        if (! isset($input["product_sku"])) {
            return false;
        }
        if (! isset($input["product_name"])) {
            return false;
        }
        if (! isset($input["product_price"])) {
            return false;
        }
        if (! isset($input["product_type"])) {
            return false;
        }
        if (! isset($input["product_type"])) {
            return false;
        }
        if (
            ! isset($input["product_size"]) &&
            ! isset($input["product_weight"]) &&
            (
                ! isset($input["product_height"]) &&
                ! isset($input["product_width"]) &&
                ! isset($input["product_length"])
            )
        ) {
            return false;
        }

        if (! $this->validateProductSKU($input["product_sku"])) {
            return false;
        }

        return true;
    }

    private function validateProductSKU(string $value) {        
        $query = "
            SELECT
                *
            FROM (
                SELECT
                    *
                FROM
                    product
                WHERE
                    NOT product_sku=:product_sku
            ) as a
            WHERE
                product_sku=:product_sku;
        ";
        $statement = $this->db->prepare($query);
        $statement->bindParam(":product_sku", $value);
        $statement->execute();
        
        if ($statement->fetchColumn()) {                
            return false; 
        }

        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response["status_code_header"] = "HTTP/1.1 422 Unprocessable Entity";
        $response["body"] = json_encode([
            "error" => "Invalid input"
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response["status_code_header"] = "HTTP/1.1 404 Not Found";
        $response["body"] = null;
        return $response;
    }
}
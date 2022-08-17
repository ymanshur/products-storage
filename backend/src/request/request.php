<?php
namespace Src\Request;

use Src\Objects\Product;
require __DIR__ . "/../objects/product.php";

class Request {
  private $db;
  private $requestMethod;

  public function __construct($db, $requestMethod) {
    $this->db = $db;
    $this->requestMethod = $requestMethod;
  }

  public function processRequest() {
    switch ($this->requestMethod) {
      case "GET":
        $product =  new Product($this->db, $this->requestMethod);
        $response = $product->getAllProduct();
        error_log(json_encode($response));
        break;
      case "POST":
        $product =  new Product($this->db, $this->requestMethod);
        $response = $product->create();
        break;
      case "PUT":
        $product =  new Product($this->db, $this->requestMethod);
        $response = $product->update();
        break;
      case "DELETE":
        $product =  new Product($this->db, $this->requestMethod);
        $response = $product->delete();
        break;
      default:
        $response = $this->pageNotFound();
        break;
    }

    header($response["status_code_header"]);
    if ($response["body"]) {
      echo $response["body"];
    }
  }

  private function pageNotFound() {
    $response["status_code_header"] = "HTTP/1.1 404 Not Found";
    $response["body"] = null;

    return $response;
  }
}

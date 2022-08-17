<?php
namespace Src\Objects;

class Product {  
  // Database connection and table name
  private $conn;
  private $requestMethod;
  private $table_name;

  // Constructor with $db as database connection
  public function __construct($db){
    $this->conn = $db;
    $this->table_name = $_ENV["DB_TABLE"];
  }

  public function getAllProduct() {
    $query = "
      SELECT
        *
      FROM
        " . $this->table_name . "
      ORDER BY
        id DESC;";

    try {
      $statement = $this->conn->query($query);
      $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }

    $response["status_code_header"] = "HTTP/1.1 200 OK";
    $response["body"] = json_encode($result);
    return $response;
  }

  // Create product
  public function create() {   
    $input = (array) json_decode(file_get_contents("php://input"), TRUE);
    if (!$this->validatePost($input)) {
      return $this->unprocessableEntityResponse();
    }

    // Sanitize
    $input["product_sku"]=htmlspecialchars(strip_tags($input["product_sku"]));
    $input["product_name"]=htmlspecialchars(strip_tags($input["product_name"]));
    $input["product_price"]=htmlspecialchars(strip_tags($input["product_price"]));
    $input["product_type"]=htmlspecialchars(strip_tags($input["product_type"]));

    // Additional query and sanitize according to product_type
    $additional_query = "";
    if ($input["product_type"] == "DVD") {
      $additional_query = "product_size=:product_size";
      $input["product_size"]=htmlspecialchars(strip_tags($input["product_size"]));
    } else if ($input["product_type"] == "Book") {
      $additional_query = "product_weight=:product_weight";
      $input["product_weight"]=htmlspecialchars(strip_tags($input["product_weight"]));
    } else if ($input["product_type"] == "Furniture") {
      $additional_query = "product_height=:product_height, product_width=:product_width, product_length=:product_length";
      $input["product_height"]=htmlspecialchars(strip_tags($input["product_height"]));
      $input["product_width"]=htmlspecialchars(strip_tags($input["product_width"]));
      $input["product_length"]=htmlspecialchars(strip_tags($input["product_length"]));
    }

    // Query to insert record
    $query = "
      INSERT INTO
        " . $this->table_name . "
      SET
        product_sku=:product_sku, product_name=:product_name, product_price=:product_price, product_type=:product_type, created_at=:created_at, " . $additional_query;

    // Set date
    $created_at = date("Y-m-d H:i:s");

    try {
      if ($this->checkSkuDuplicate($input)) {                
        $response["status_code_header"] = "HTTP/1.1 400 Bad Request";
        $response["body"] = json_encode(array("message" => "The SKU you entered is already used"));
        return $response;   
      }

      // Prepare query
      $stmt = $this->conn->prepare($query);
  
      // bind values
      $stmt->bindParam(":product_sku", $input["product_sku"]);
      $stmt->bindParam(":product_name", $input["product_name"]);
      $stmt->bindParam(":product_price", $input["product_price"]);
      $stmt->bindParam(":product_type", $input["product_type"]);
      $stmt->bindParam(":created_at", $created_at);
      if ($input["product_type"] == "DVD") {
        $stmt->bindParam(":product_size", $input["product_size"]);
      } else if ($input["product_type"] == "Book") {
        $stmt->bindParam(":product_weight", $input["product_weight"]);
      } else if ($input["product_type"] == "Furniture") {
        $stmt->bindParam(":product_height", $input["product_height"]);
        $stmt->bindParam(":product_width", $input["product_width"]);
        $stmt->bindParam(":product_length", $input["product_length"]);
      }
      $stmt->execute();
      $stmt->rowCount();
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }

    $response["status_code_header"] = "HTTP/1.1 201 Created";
    $response["body"] = json_encode(array("message" => "Product Created"));
    return $response;        
  }

  // Update product
  public function update() {   
    $input = (array) json_decode(file_get_contents("php://input"), TRUE);
    if (!isset($input["id"])) {
      $response["status_code_header"] = "HTTP/1.1 400 Bad Request";
      $response["body"] = json_encode(array("message" => "Please input the id."));
      return $response; 
    }

    $params = "";
    foreach($input as $key => $val) {
      if ($key != "id") {
        $params .= "$key=:$key, ";
      }
    }
    
    if (count($input) >= 2) {
      $params = substr($params, 0, -2);
    }

    $query = "
      UPDATE
        " . $this->table_name . "
      SET
        $params
      WHERE
        id=:id;";

    try {
      $stmt = $this->conn->prepare($query);
      $stmt->execute($input);
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }

    $response["status_code_header"] = "HTTP/1.1 201 Created";
    $response["body"] = json_encode(array("message" => "Updated successfully."));
    return $response;               
  }

  public function delete() {
    if (isset($_GET["id"])) {            
      $ids = preg_replace("/\s/", "", $_GET["id"]);
      $strToArr = explode(",", $ids);
      $urlParams = str_repeat("?,", count($strToArr) - 1) . "?";
      try {
          $stmt = $this->conn->prepare("DELETE FROM " . $this->table_name . " WHERE id IN ($urlParams)");
          $stmt->execute($strToArr);
      } catch (\PDOException $e) {
          exit($e->getMessage());
      }
      $response["status_code_header"] = "HTTP/1.1 200 OK";
      $response["body"] = json_encode(array("message" => "Product Has Been Deleted"));
      return $response;  
    }
    $response["status_code_header"] = "HTTP/1.1 400 Bad Request";
    $response["body"] = json_encode(array("message" => "Please input id parameter."));
    return $response;        
  }

  private function validatePost($input)
  {
    if (
      !empty($input["product_sku"]) &&
      !empty($input["product_name"]) &&
      !empty($input["product_price"]) &&
      !empty($input["product_type"]) &&
      (
        !empty($input["product_size"]) ||
        !empty($input["product_weight"]) ||
        (
          !empty($input["product_height"]) &&
          !empty($input["product_width"]) &&
          !empty($input["product_length"])
        )
      )
    ) {
      return true;
    }

    return false;
  }

  private function checkSkuDuplicate($input) {        
    $query = "SELECT * FROM " . $this->table_name . " WHERE product_sku=:product_sku";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":product_sku", $input["product_sku"]);
    $stmt->execute();
    
    if($stmt->fetchColumn()) {                
      return true; 
    }

    return false;
  }

  private function unprocessableEntityResponse() {
    $response["status_code_header"] = "HTTP/1.1 422 Unprocessable Entity";
    $response["body"] = json_encode([
      "error" => "Invalid input"
    ]);
    return $response;
  }
}

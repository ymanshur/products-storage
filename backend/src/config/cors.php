<?php
namespace Src\Config;

class Cors{  
  private $http_origin;

  public function cors() {
    $this->http_origin = "http://localhost:8080";
    // Allow from any origin
    if (isset($this->http_origin)) {
      // Decide if the origin in $this->http_origin is one
      // you want to allow, and if so:
      header("Access-Control-Allow-Origin: {$this->http_origin}");
      header("Access-Control-Allow-Credentials: true");
      header("Access-Control-Max-Age: 86400");  // cache for 1 day
      header("Content-Type: application/json");
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {

      if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");         

      if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
        header("Access-Control-Allow-Headers: {$_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]}");
      exit(0);
    }
  }
}
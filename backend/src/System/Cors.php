<?php
namespace Src\System;

class Cors
{  
  private $client_http_origin;

  public function setCors()
  {
    $this->client_http_origin = $_ENV['CLIENT_HTTP_ORIGIN'];

    error_log($this->client_http_origin);
  
    // Allow from any origin
    if (isset($this->client_http_origin)) {
      // Decide if the origin in $this->client_http_origin is one
      // you want to allow, and if so:
      header("Access-Control-Allow-Origin: {$this->client_http_origin}");
      header("Access-Control-Allow-Credentials: true");
      header("Access-Control-Max-Age: 86400");  // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {

      if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
        // May also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS");         

      if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

      exit(0);
    }
  }
}
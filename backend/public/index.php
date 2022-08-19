<?php
require "../bootstrap.php";

use Src\System\{Cors};
use Src\Controllers\{ProductController};

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$cors = new Cors();
$cors->setCors();

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$uriFragments = explode("/", $uri);
[, $baseURL, $route] = $uriFragments;

// All of our endpoints start with '/restapi'
// and followed by '/products'
// everything else results in a 404 Not Found
if ($baseURL !== "restapi" || ! $route === "products") {
    http_response_code(404);
    exit();
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

// The user id is, of course, optional and must be a number:
$productId = null;
if (isset($uriFragments[3])) {
    $productId = (int) $uriFragments[3];
}

// Pass the request method and user ID to the ProductController and process the HTTP request:
$controller = new ProductController($dbConnection, $requestMethod, $productId);
$controller->processRequest();
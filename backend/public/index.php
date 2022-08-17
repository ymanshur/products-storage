<?php
require "../vendor/autoload.php";

use Src\Config\Database;
use Src\Config\Cors;
use Src\Request\Request;

require __DIR__ . "/../src/config/cors.php";
require __DIR__ . "/../src/config/database.php";
require __DIR__ . "/../src/request/request.php";

echo $_SERVER["REQUEST_URI"];
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$uri = explode("/", $uri);

if ($uri[1] !== "api" || count($uri) !== 2) {
  header("HTTP/1.1 404 Not Found");
  exit();
}

$setCors = new Cors();
$setCors->cors();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeload();

$database = new Database();
$dbConn = $database->getConnection();

$requestMethod = $_SERVER["REQUEST_METHOD"];

// Pass the request method and post ID to the Post and process the HTTP request:
$controller = new Request($dbConn, $requestMethod);
$controller->processRequest();

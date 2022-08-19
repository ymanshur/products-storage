<?php
require "../bootstrap.php";

use Firebase\JWT\{JWT};
use Firebase\JWT\{Key};
use Src\System\{Cors};
use Src\Controllers\{ProductController};

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$cors = new Cors();
$cors->setCors();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uriFragments = explode('/', $uri);
[, $baseURL, $route] = $uriFragments;

// All of our endpoints start with '/restapi'
// and followed by '/obtain-token' or '/products'
// everything else results in a 404 Not Found
$allowedRoutes = array('obtain-token', 'products');
if ($baseURL !== 'restapi' || ! in_array($route, $allowedRoutes)) {
    http_response_code(404);
    exit();
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($route === 'obtain-token') {
    if ($requestMethod !== 'POST') {
        http_response_code(405);
        exit();
    }

    $payload = file_get_contents('php://input');
    $payload = json_decode($payload);
    if (! empty($_POST)) {
        $payload = (object) $_POST;
    }

    if (! isset($payload->email) || ! isset($payload->password)) {
        http_response_code(400);
        exit();
    }

    $user = array(
        'email' => 'ymanshur@gmail.com',
        'password' => 'OTbclNzjZV'
    );

    if (($payload->email !== $user['email']) || $payload->password !== $user['password']) {
        header('HTTP/1.1 400 Bad Request');
        $responseBody = json_encode(
            array('message' => 'Email atau password tidak sesuai.')
        );
        echo $responseBody;
        exit();
    }

    $tokenExpiry = time() + (15 * 60);
    $token = JWT::encode(array(
        'email' => $payload->email,
        'exp' => $tokenExpiry
    ), $_ENV['ACCESS_TOKEN_SECRET'], 'HS256');
    $responseBody = json_encode(
        array(
            'accessToken' => $token,
            'expiry' => date(DATE_ISO8601, $tokenExpiry)
        )
    );
    echo $responseBody;
    exit();
}

// $headers = getallheaders();
// if (! isset($headers['Authorization'])) {
//     http_response_code(401);
//     exit();
// }

// try {
//     list(, $token) = explode(' ', $headers['Authorization']);
//     JWT::decode($token, new Key($_ENV['ACCESS_TOKEN_SECRET'], 'HS256'));
// } catch (RuntimeException | DomainException $th) {
//     http_response_code(401);
//     exit();
// }

// The user id is, of course, optional and must be a number:
$productId = null;
if (isset($uriFragments[3])) {
    $productId = (int) $uriFragments[3];
}

// Pass the request method and user ID to the ProductController and process the HTTP request:
$controller = new ProductController($dbConnection, $requestMethod, $productId);
$controller->processRequest();
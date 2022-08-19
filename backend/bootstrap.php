<?php
require 'vendor/autoload.php';

use Src\System\{DatabaseConnector};

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// test code, should output:
// root
// when you run $ php bootstrap.php
// echo $_ENV['DB_USERNAME'] . "\n";

$dbConnection = (new DatabaseConnector())->getConnection();
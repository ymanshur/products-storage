<?php
namespace Src\System;

class DatabaseConnector
{
    private $dbConnection;

    public function __construct()
    {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db   = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];

        try {
            $this->dbConnection = new \PDO(
                "mysql:host=" . $host . ";charset=utf8mb4;dbname=" . $db,
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}
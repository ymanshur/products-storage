<?php
namespace Src\Config;

class Database {  
  // Specify your own database credentials
  private $host;
  private $db_name;
  private $username;
  private $password;
  private $conn;

  // Get the database connection
  public function getConnection() { 
    $this->host = $_ENV["DB_HOST"];
    $this->db_name = $_ENV["DB_DATABASE"];
    $this->username = $_ENV["DB_USERNAME"];
    $this->password = $_ENV["DB_PASSWORD"];
    $this->conn = null;

    try {
      $this->conn = new \PDO(
        "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
        $this->username,
        $this->password
      );
    } catch (\PDOException $exception) {
      echo "Connection error: " . $exception->getMessage();
    }

    return $this->conn;
  }
}

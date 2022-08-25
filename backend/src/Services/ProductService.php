<?php
namespace Src\Services;

class ProductService
{
    private $db;
    private $dbTable;

    public function __construct($db)
    {
        $this->db = $db;
        $this->dbTable = $_ENV["DB_TABLE"];
    }

    public function findAll()
    {}

    public function find($id)
    {}

    public function insert(array $input)
    {}

    public function update($id, array $input)
    {}

    public function delete($id)
    {}
}
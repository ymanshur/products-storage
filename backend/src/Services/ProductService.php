<?php
namespace Src\Services;

class ProductService
{
    private $db;
    private $dbTable = "product";

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {}

    public function find($id)
    {}

    public function insert(array $input)
    {   }

    public function update($id, array $input)
    {}

    public function delete($id)
    {}
}
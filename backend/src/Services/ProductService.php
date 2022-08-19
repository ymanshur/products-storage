<?php
namespace Src\Services;

use Src\Models\{Product};

class ProductService
{
    private $db;
    private $dbTable = 'product';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $query = "
            SELECT 
                *
            FROM (
                SELECT 
                    *
                FROM
                    " . $this->dbTable . "
            ) as a
            ORDER BY
                id DESC;
        ";

        try {
            $statement = $this->db->query($query);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT 
                *
            FROM
                " . $this->dbTable . "
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input)
    {
        // Sanitize
        $input['product_sku'] = htmlspecialchars(strip_tags($input['product_sku']));
        $input['product_name'] = htmlspecialchars(strip_tags($input['product_name']));
        $input['product_price'] = htmlspecialchars(strip_tags($input['product_price']));
        $input['product_type'] = htmlspecialchars(strip_tags($input['product_type']));
        // Additional query and sanitize according to product_type
        $additional_query = '';
        switch ($input['product_type']) {
            case 'dvd':
                $additional_query = 'product_size=:product_size';
                $input['product_size'] = htmlspecialchars(strip_tags($input['product_size']));
                break;
            case 'book':
                $additional_query = 'product_weight=:product_weight';
                $input['product_weight'] = htmlspecialchars(strip_tags($input['product_weight']));
                break;
            case 'furniture':
                $additional_query = 'product_height=:product_height, product_width=:product_width, product_length=:product_length';
                $input['product_height'] = htmlspecialchars(strip_tags($input['product_height']));
                $input['product_width'] = htmlspecialchars(strip_tags($input['product_width']));
                $input['product_length'] = htmlspecialchars(strip_tags($input['product_length']));
                break;
            default:
                # code...
                break;
        }

        $query = "
            INSERT INTO
                " . $this->dbTable . " 
            SET
                product_sku=:product_sku, product_name=:product_name, product_price=:product_price, product_type=:product_type, created_at=:created_at,
        " . $additional_query;

        try {
            $statement = $this->db->prepare($query);

            // Bind values
            $statement->bindParam(':product_sku', $input['product_sku']);
            $statement->bindParam(':product_name', $input['product_name']);
            $statement->bindParam(':product_price', $input['product_price']);
            $statement->bindParam(':product_type', $input['product_type']);
            $statement->bindParam(':created_at', date('Y-m-d H:i:s'));
            switch ($input['product_type']) {
                case 'dvd':
                    $statement->bindParam(':product_size', $input['product_size']);
                    break;
                case 'book':
                    $statement->bindParam(':product_weight', $input['product_weight']);
                    break;
                case 'furniture':
                    $statement->bindParam(':product_height', $input['product_height']);
                    $statement->bindParam(':product_width', $input['product_width']);
                    $statement->bindParam(':product_length', $input['product_length']);
                    break;
                default:
                    # code...
                    break;
            }

            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, array $input)
    {
        $input['updated_at'] = date('Y-m-d H:i:s');
        
        $params = "";
        foreach ($input as $key => $value) {
            if ($key != "id") {
                $params .= "$key=:$key, ";
            }
        }

        if (count($input) >= 2) {
            $params = substr($params, 0, -2);
        }

        $query = "
            UPDATE " . $this->dbTable . "
            SET 
                $params
            WHERE id = :id;
        ";

        error_log(json_encode(array_merge(
            array('id' => (int) $id),
            $input
        )));

        try {
            $statement = $this->db->prepare($query);
            $statement->execute(array_merge(
                array('id' => (int) $id),
                $input
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM " . $this->dbTable . "
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => (int) $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
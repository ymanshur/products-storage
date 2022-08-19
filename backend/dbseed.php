<?php
require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS product (
        id INT NOT NULL AUTO_INCREMENT,
        product_sku VARCHAR(255) NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        product_type VARCHAR(255) NOT NULL,
        product_price FLOAT(7,2) NOT NULL,
        product_size FLOAT(7,2),
        product_weight FLOAT(7,2),
        product_hight FLOAT(7,2),
        product_width FLOAT(7,2),
        product_length FLOAT(7,2),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE (product_sku)
    );

    INSERT INTO product
        (id, product_sku, product_name, product_type, product_price, product_size, product_weight, product_hight, product_width, product_length)
    VALUES
        (1, 'disc-ai-2021', 'A.I. Artificial Intelligence', 'dvd', 20.22, 400, NULL, NULL, NULL, NULL),
        (2, 'disc-ta-2022', 'The Abyss', 'dvd', 23.05, 520, NULL, NULL, NULL, NULL),
        (3, 'disc-ab-2023', 'Adaptation', 'dvd', 30.00, 380, NULL, NULL, NULL, NULL),
        (4, 'book-ujj-2021', 'Ulysses by James Joyce', 'book', 80.00, NULL, 15.6, NULL, NULL, NULL),
        (5, 'book-mdhm-2022', 'Moby Dick by Herman Melville', 'book', 62.02, NULL, 10.5, NULL, NULL, NULL),
        (6, 'book-wplt-2023', 'War and Peace by Leo Tolstoy', 'book', 50.50, NULL, 12.3, NULL, NULL, NULL),
        (7, 'furn-ls-2021', 'Livien Sofa', 'furniture', 70.00, NULL, NULL, 50, 60, 180),
        (8, 'furn-it-2022', 'IKEA Table', 'furniture', 108.50, NULL, NULL, 120, 100, 200),
        (9, 'furn-rsb-2023', 'Red Sun Bed', 'furniture', 120.03, NULL, NULL, 60, 250, 300);
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Success!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}
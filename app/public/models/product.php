<?php

require_once 'Database.php';

class Product
{
    public static function all()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

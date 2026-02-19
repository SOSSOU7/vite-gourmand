<?php

class Database
{
    public static function getConnection()
    {
        $host = 'mysql';
        $db   = 'vitegourmand';
        $user = 'hermann';
        $pass = 'hermann123';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}

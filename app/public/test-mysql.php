<?php

$host = 'mysql';
$db   = 'vitegourmand';
$user = 'hermann';
$pass = 'hermann123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2 style='color:green;'>Connexion MySQL réussie !</h2>";
} catch (PDOException $e) {
    echo "<h2 style='color:red;'>Erreur de connexion : " . $e->getMessage() . "</h2>";
}

<?php
class Database {
    private $host = 'ryfqldzbliwmq6g5.chr7pe7iynqr.eu-west-1.rds.amazonaws.com';
    private $dbname = 'Uunf15yvl4C3Zqw7';
    private $username = 'bysrsjjbkrz7699l';
    private $password = 'b4ecak4coz4w1nc8';
    public $pdo;

   public function __construct() {
        try {
            $url = getenv('JAWSDB_URL');
            if ($url) {
                // Configuration HEROKU
                $dbparts = parse_url($url);
                $this->host = $dbparts['host'];
                $this->username = $dbparts['user'];
                $this->password = $dbparts['pass'];
                $this->dbname = ltrim($dbparts['path'], '/');
            } else {
                // Configuration LOCALE (Docker)
                $this->host = 'mysql';
                $this->dbname = 'vite_gourmand';
                $this->username = 'root';
                $this->password = 'root';
            }
 
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}
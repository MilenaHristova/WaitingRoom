<?php
class Database {
    private $pdo;
    private static $instance = null;
    
    private function __construct(){
        $host = 'localhost';
        $db = 'waiting_room';
        $user = 'test_user';
        $password = 'pass';

        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
        
        try {
        	$this->pdo = new PDO($dsn, $user, $password);
        
        } catch (PDOException $e) {
           echo 'failed';
           echo $e->getMessage();
        }
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
 
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}

?>
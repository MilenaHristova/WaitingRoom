<?php
class Database {
    private $pdo;
    private static $instance = null;
    
    private function __construct(){
        $config = include($_SERVER['DOCUMENT_ROOT'].'\WaitingRoom\config.php');
        
        $host = $config['DB_SERVERNAME'];
        $db = $config['DB_NAME'];
        $user = $config['DB_USERNAME'];
        $password = $config['DB_PASSWORD'];

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
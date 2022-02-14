<?php
require_once('connect_db.php');

class UsersModel{
    private $pdo;
    
    public function __construct(){
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }
    
    function getByUsername($username){
        $query = "SELECT * FROM users WHERE username LIKE \"$username\"";
        $statements = $this->pdo->query($query);
        
        if($statements->rowCount() == 0){
            return FALSE;
        } else {
            return $statements->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    function searchByFn($fn){
        $query = 'SELECT name, user_id FROM users WHERE faculty_number = '.$fn;
        $st = $this->pdo->query($query);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    
}











?>
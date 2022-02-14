<?php
require_once('connect_db.php');

class LobbyModel{
    private $pdo;
    
    public function __construct(){
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }
    
    function getAllRooms(){
	   $query = 'SELECT name, description, room_id FROM rooms';
	   $stmt = $this->pdo->query($query);
	   $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	   
	   return $rows;
    }

    function searchRooms($str){
        $query = 'SELECT name, description, room_id FROM rooms WHERE name LIKE ?';
        $stmt= $this->pdo->prepare($query);
        $stmt->execute(['%'.$str.'%']);
        //$stmt = $pdo->query($query);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $rows;
    }
}




?>
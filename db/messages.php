<?php

require_once('connect_db.php');

class MessagesModel{
    private $pdo;
    
    public function __construct(){
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
    }
    
    function getMessages($room_id){
        $query = 'SELECT * FROM messages WHERE room_id = '.$room_id. ' ORDER BY time';
        $statements = $this->pdo->query($query);
        $messages = $statements->fetchAll(PDO::FETCH_ASSOC);
        return $messages;
    }
    
    function sendMessage($room_id, $text, $author_id, $author_name, $send_to, $time){
	    $insert_query = "INSERT INTO messages (room_id, text, author_id, author_name, send_to, time) VALUES (?, ?, ?, ?, ?, ?)";
	    $stmt= $this->pdo->prepare($insert_query);
        $stmt->execute([$room_id, $text, $author_id, $author_name, $send_to, $time]);
    }
    
}








?>
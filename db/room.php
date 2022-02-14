<?php

require_once('connect_db.php');

class RoomModel{
    private $pdo;
    
    public function __construct(){
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
        date_default_timezone_set('Europe/Sofia');
    }
    
    function loadRoomDescr($room_id){
        $query = 'SELECT * FROM rooms WHERE room_id = '.$room_id;
        $st = $this->pdo->query($query);
        $result = $st->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    function insertRoomDetails($title, $descr, $url, $passwd, $type, $userid){      
        $sql = 'SELECT avg_time FROM room_type WHERE id = ?';
        $st= $this->pdo->prepare($sql); 
        $st->execute([$type]);
        $res = $st->fetch(PDO::FETCH_ASSOC);
        $avg_time = $res['avg_time'] > 0 ? $res['avg_time'] : NULL;
        
        $query = 'INSERT INTO rooms(creator_id, moderator_id, name, description,
                type_id, meeting_password, url, avg_time) values(?, ?, ?, ?, ?, ?, ?, ?)';
                    
        $stmt= $this->pdo->prepare($query);
        $stmt->execute([$userid, $userid, $title, $descr, $type, $passwd, $url, $avg_time]);
        
        return $this->pdo->lastInsertId();
    }
    
    function insertRoomType($name, $avg_time){
        $sql = 'INSERT INTO room_type(name, avg_time) VALUES (?,?)';
        $stmt= $this->pdo->prepare($sql);
        $stmt->execute([$name, $avg_time]);
    }
    
    function getRoomTypes(){
        $query = 'SELECT * FROM room_type';
        $st = $this->pdo->query($query);
        $res = array();
        
        while(($row = $st->fetch(PDO::FETCH_ASSOC)) != FALSE){
            $r = array();
            if ($row['avg_time'] != NULL){
                $time_text = "(~ {$row['avg_time']} минути)"; 
            }
            else{
                $time_text = '';
            }
            
            $r['time_text'] = $time_text;
            $r['id'] = $row['id'];
            $r['name'] = $row['name'];
            
            array_push($res, $r);
        }
        
        return $res;
    }
    
    function setBreak($room_id, $mins){
        $now = time();
        $end = $now + (60 * $mins);
        $end_time = date('Y-m-d H:i', $end);

        $sql = 'UPDATE rooms SET break_until = '.($this->pdo->quote($end_time)).' WHERE room_id = '.$room_id;
        $this->pdo->exec($sql);    
    }
    
    function getBreak($room_id){
        $sql = 'SELECT break_until FROM rooms WHERE room_id = '.$room_id;
        $st = $this->pdo->query($sql);
        $res = $st->fetch(PDO::FETCH_ASSOC);
        
        return $res == FALSE ? FALSE : $res['break_until'];
    }
    
    function getAvgTime($room_id){
        $sql = 'SELECT avg_time FROM rooms WHERE room_id = '.$room_id;
	    $st = $this->pdo->query($sql);
        $res = $st->fetch(PDO::FETCH_ASSOC);
	   
	    return $res == FALSE ? FALSE : $res['avg_time'];
    }
    
    function updateAvgTimeOfType($room_id){
	    $sql = 'SELECT avg_time, type_id FROM rooms WHERE room_id = '.$room_id;
	    $st = $this->pdo->query($sql);
        $res = $st->fetch(PDO::FETCH_ASSOC);
	    $new_avg = $res['avg_time'];
	    $type_id = $res['type_id'];
	    if($type_id != NULL){
	   	   $sql = 'SELECT avg_time FROM room_type WHERE id = ?';
	   	   $st= $this->pdo->prepare($sql); 
	   	   $st->execute([$type_id]);
	   	   $res = $st->fetch(PDO::FETCH_ASSOC);
	   	   $old_avg = $res['avg_time'] > 0 ? $res['avg_time'] : $new_avg;
	   
	   	   $sql = 'UPDATE room_type SET avg_time = ? WHERE id = ?';
	   	   $st= $this->pdo->prepare($sql); 
	   	   $st->execute([($old_avg + $new_avg) / 2, $type_id]);
	   }
    }   
    
    function checkIfCreator($room_id, $user_id){
	   $sql = 'SELECT creator_id FROM rooms WHERE room_id = '.$room_id;
	   $st = $this->pdo->query($sql);
	   $res = $st->fetch(PDO::FETCH_ASSOC);
	   return $res['creator_id'] == $user_id;
    }
    
    function deleteRoom($room_id){
	   $sql = 'DELETE FROM rooms WHERE room_id = '.$room_id;
	   $st = $this->pdo->query($sql);
    }
    
    function setModerator($room_id, $id){
	   $sql = 'UPDATE rooms SET moderator_id ='.$id.' WHERE room_id = '.$room_id;
	   $this->pdo->exec($sql);
    }
    
    function checkIfModerator($room_id, $user_id){
	    $sql = 'SELECT moderator_id FROM rooms WHERE room_id = '.$room_id;
	    $st = $this->pdo->query($sql);
	    $res = $st->fetch(PDO::FETCH_ASSOC);
	    return $res['moderator_id'] == $user_id;
    }
}



















?>



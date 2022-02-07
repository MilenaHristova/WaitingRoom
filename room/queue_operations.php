<?php

session_start();
require_once('../connect_db.php');

date_default_timezone_set('Europe/Sofia');

function loadQueue($room_id, $count){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'UPDATE room_student SET waiting = FALSE WHERE room_id = '.$room_id.' AND is_next = TRUE';
    $pdo->exec($query);
    
    $query = 'SELECT user_id, u.name, faculty_number FROM users u
         JOIN room_student rs ON u.user_id = rs.student_id
         WHERE rs.room_id = '.$room_id.' AND rs.waiting = TRUE LIMIT '.$count.';';
    
    $res = array();
    
    $st = $pdo->query($query);
    while(($row = $st->fetch(PDO::FETCH_ASSOC)) != FALSE){
        array_push($res, array("name" => $row["name"], "fn" => $row["faculty_number"]));
    }
    
    return $res;
}

function loadRoomDescr($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    $query = 'SELECT * FROM rooms WHERE room_id = '.$room_id;
    $st = $pdo->query($query);
    $result = $st->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function updateNext($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT team FROM room_student WHERE room_id = '.$room_id.' AND is_next = TRUE AND is_temp = FALSE;';
    $st = $pdo->query($query);
    $result = $st->fetch(PDO::FETCH_ASSOC);
    
    if($result == FALSE){
        $curr = 0;
    } else {
        $curr = $result["team"]; 
    }
    
    $query = 'UPDATE room_student 
    SET is_next = FALSE WHERE room_id = '.$room_id.';';
       $pdo->exec($query);
    
    $query = 'UPDATE room_student 
    SET is_next = TRUE WHERE room_id = '.$room_id.' AND team = '.($curr + 1).';';
    $pdo->exec($query);
    
    header("Location: queue.php?room=".$room_id);
    exit();
}

function getNext($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $sql = 'SELECT faculty_number FROM users u 
              JOIN room_student rs ON u.user_id = rs.student_id
              WHERE rs.room_id = '.$room_id.' AND rs.is_next = 1';
    
    $st = $pdo->query($sql);
    
    $res = array();
    while(($row = $st->fetch(PDO::FETCH_ASSOC)) != FALSE){
        array_push($res, $row["faculty_number"]);
    }
   
    if(empty($res)){
        //return FALSE;
        return array();
    }
    
    return $res;
}

function searchByFn($fn){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT name, user_id FROM users WHERE faculty_number = '.$fn;
    $st = $pdo->query($query);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function getStudentTime($id, $room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT time FROM room_student WHERE student_id = '.$id.' AND room_id = '.$room_id;
    $st = $pdo->query($query);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row == FALSE ? FALSE : $row['time'];
}

function setBreak($room_id, $mins){
    $now = time();
    $end = $now + (60 * $mins);
    $end_time = date('Y-m-d H:i', $end);
    
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $sql = 'UPDATE rooms SET break_until = '.($pdo->quote($end_time)).' WHERE room_id = '.$room_id;
    $pdo->exec($sql);    
}

function getBreak($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    $sql = 'SELECT break_until FROM rooms WHERE room_id = '.$room_id;
    $st = $pdo->query($sql);
    $res = $st->fetch(PDO::FETCH_ASSOC);
    
    return $res == FALSE ? FALSE : $res['break_until'];
}

if(isset($_POST["next"])){
    updateNext($_POST["room_id"]);
} elseif(isset($_POST["break"])){
    $room_id = $_POST["room_id"];
    if(!isset($_POST["mins"])){
        header("Location: ../room/queue.php?room=".$room_id);
        exit(); 
    
    }
    setBreak($room_id, $_POST["mins"]);
    header("Location: ../room/queue.php?room=".$room_id);
    exit();
}


















?>

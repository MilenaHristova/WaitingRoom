<?php

session_start();
require_once('../connect_db.php');

function loadQueue($room_id, $count){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT next_fn FROM rooms WHERE room_id = '.$room_id;
    $st = $pdo->query($query);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    $next_fn = $row["next_fn"];
    
    if($next_fn != null){
        $query = 'SELECT user_id FROM users WHERE faculty_number = '.$next_fn;
        $st = $pdo->query($query);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        $id = $row["user_id"];
        
        $query = 'UPDATE room_student SET waiting = FALSE WHERE student_id = '.$id;
        $pdo->exec($query);
    }
    
    $query = 'SELECT user_id, u.name, faculty_number FROM users u
         JOIN room_student rs ON u.user_id = rs.student_id JOIN rooms r on rs.room_id = r.room_id
         WHERE r.room_id = '.$room_id.' AND rs.waiting = TRUE LIMIT '.$count.';';
    
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
    
    $query = 'SELECT rs.place FROM users u 
              JOIN room_student rs ON u.user_id = rs.student_id 
              JOIN rooms r on rs.room_id = r.room_id 
              WHERE r.room_id = '.$room_id.' AND u.faculty_number = r.next_fn;';
    
    $st = $pdo->query($query);
    $result = $st->fetch(PDO::FETCH_ASSOC);
    
    if($result == FALSE){
        $curr = 0;
    } else {
        $curr = $result["place"]; 
    }
    
    $query = 'SELECT user_id, faculty_number FROM users u
         JOIN room_student rs ON u.user_id = rs.student_id JOIN rooms r on rs.room_id = r.room_id
         WHERE r.room_id = '.$room_id.' AND rs.place = '.($curr + 1).';';
    
    $st = $pdo->query($query);
    $result = $st->fetch(PDO::FETCH_ASSOC);
    $next_fn = $result["faculty_number"] != null ? $result["faculty_number"] : 0;
    $next_id = $result["user_id"];
    
    $query = 'UPDATE rooms SET next_fn = '.$next_fn.' WHERE room_id = '.$room_id;
    $pdo->exec($query);
    
    if($next_id != null){
        $query = 'UPDATE room_student SET waiting = FALSE WHERE student_id = '.$next_id;
        $pdo->exec($query);
    }
    
    header("Location: queue.php?room=".$room_id);
    exit();
}

function getNext($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT next_fn FROM rooms WHERE room_id = '.$room_id;
    $st = $pdo->query($query);
    $res = $st->fetch(PDO::FETCH_ASSOC);
    return $res["next_fn"];
}

if(isset($_POST["next"])){
    updateNext($_POST["room_id"]);
}


















?>

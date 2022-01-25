<?php

session_start();
require_once('connect_db.php');

function loadQueue($room_id, $count){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT user_id, u.name, faculty_number FROM users u
         JOIN room_student rs ON u.user_id = rs.student_id JOIN rooms r on rs.room_id = r.room_id
         WHERE r.room_id = '.$room_id.' LIMIT '.$count.';';
    
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

function getNext($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $curr = $_SESSION["current_place"];
    echo 'CURRENT: '.$curr;
    $query = 'SELECT user_id, faculty_number FROM users u
         JOIN room_student rs ON u.user_id = rs.student_id JOIN rooms r on rs.room_id = r.room_id
         WHERE r.room_id = '.$room_id.' AND rs.place = '.($curr + 1).';';
    
    $st = $pdo->query($query);
    $result = $st->fetch(PDO::FETCH_ASSOC);
    $_SESSION["current_fn"] = $result["faculty_number"];
    $_SESSION["current_place"] = $curr + 1;
    
    header("Location: queue.php?room=".$room_id);
    exit();
}

if(isset($_POST["next"])){
    getNext($_POST["room_id"]);
}


















?>
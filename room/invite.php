<?php
require '../connect_db.php';

if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}

function isStudentInRoom($room_id, $student_id, $pdo){
    $sql = 'SELECT * FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$student_id;
    
    $st = $pdo->query($sql);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row != FALSE;
}

if(isset($_POST["invite_temp"]) | isset($_POST["invite"])){
    if(!isset($_SESSION['user_role']) | $_SESSION['user_role'] == 1){
        header("Location: ../lobby/lobby.php");
        exit();
    } 
    
    $id = $_POST["student_id"];
    $room_id = $_POST["room_id"];
    
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    if(isset($_POST["invite_temp"])){
        $waiting = 1;
    } else {
        $waiting = 0;
    }
    
    if(isStudentInRoom($room_id, $id, $pdo)){
        $sql = 'UPDATE room_student 
        SET waiting = '.$waiting.', in_room = TRUE WHERE room_id = '.$room_id.' AND student_id = '.$id.';';

        $pdo->exec($sql);
    } else {
        $sql = 'SELECT MAX(team) FROM room_student WHERE room_id = '.$room_id.';';
        $st = $pdo->query($query);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if($row){
            $team = $row['team'] + 1;
        } else {
            $team = 1;
        }
        
        $sql = 'INSERT INTO room_student(room_id, student_id, team, in_room, waiting) VALUES (?, ?, ?, ?)';
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$room_id, $id, $team, TRUE, $waiting]);
    }
    
    header("Location: ../room/room.php?room=".$room_id);
    exit();
}





?>
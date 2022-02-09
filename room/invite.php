<?php
require '../connect_db.php';

function isStudentInRoom($room_id, $student_id, $pdo){
    $sql = 'SELECT * FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$student_id;
    
    $st = $pdo->query($sql);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row != FALSE;
}

if(isset($_POST["invite_temp"]) | isset($_POST["invite"])){
    $id = $_POST["student_id"];
    $room_id = $_POST["room_id"];
    
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    if(isset($_POST["invite_temp"])){
        $waiting = 1;
    } else {
        $waiting = 0;
    }
    
    $sql = 'UPDATE room_student 
    SET is_next = FALSE WHERE room_id = '.$room_id.';';
    $pdo->exec($sql);
    
    if(isStudentInRoom($room_id, $id, $pdo)){
        $sql = 'UPDATE room_student 
        SET is_next = 1, waiting = '.$waiting.' WHERE room_id = '.$room_id.' AND student_id = '.$id.';';

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
        
        $sql = 'INSERT INTO room_student(room_id, student_id, team, is_next, waiting) VALUES (?, ?, ?, ?)';
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$room_id, $id, $team, TRUE, $waiting]);
    }
    
    header("Location: ../room/room.php?room=".$room_id);
    exit();
}





?>
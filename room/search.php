<?php
require '../connect_db.php';

if(isset($_POST["invite_temp"])){
    $id = $_POST["student_id"];
    $room_id = $_POST["room_id"];
    
    echo $room_id;
    
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $sql = 'UPDATE room_student SET is_next = TRUE, is_temp = TRUE WHERE room_id = '.$room_id.' AND student_id = '.$id;
    
    $pdo->exec($sql);
    
    header("Location: ../room/queue.php?room=".$room_id);
    exit();
}






?>
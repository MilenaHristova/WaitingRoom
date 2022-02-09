<?php
require_once('../connect_db.php');

$id = $_REQUEST["id"];
$room_id = $_REQUEST["room"];

if($id != '' && $room_id != ''){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'UPDATE room_student 
    SET in_room = FALSE WHERE room_id = '.$room_id.' AND student_id = '.$id.';';
    $pdo->exec($query);
}

header("Location: room.php?room=".$room_id);
exit();
    
    
?>
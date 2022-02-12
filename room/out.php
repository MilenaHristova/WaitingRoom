<?php
require_once('../connect_db.php');
date_default_timezone_set('Europe/Sofia');

$id = $_REQUEST["id"];
$room_id = $_REQUEST["room"];

if($id != '' && $room_id != ''){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
	$date = date('Y-m-d H:i:s', time());
    $query = 'UPDATE room_student 
    SET in_room = FALSE, out_time= ? WHERE room_id = ? AND student_id = ?';
	$stmt = $pdo->prepare($query);
	$stmt->execute([$date, $room_id, $id]);
	
	$query_in_time = 'SELECT in_time FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$id;
	$st = $pdo->query($query_in_time);
    $result = $st->fetch(PDO::FETCH_ASSOC);
	$date_in = new DateTime($result['in_time']);
	$date_out = new DateTime($date);
	$diff = date_diff($date_out, $date_in);
	$minutes = $diff->days * 24 * 60;
	$minutes += $diff->h * 60;
	$minutes += $diff->i;
	
	$query = 'SELECT avg_time, passed_people FROM rooms WHERE room_id ='.$room_id;
	$st = $pdo->query($query);
    $result = $st->fetch(PDO::FETCH_ASSOC);
	$avg_time = $result['avg_time'];
	$passed = $result['passed_people'];
	
	$avg_time = ($avg_time * $passed + $minutes) / ($passed + 1);
	
	
	
	$sql = 'UPDATE rooms SET passed_people = passed_people + 1, avg_time = '.$avg_time.' WHERE room_id ='.$room_id;
	$pdo->exec($sql);
}

header("Location: room.php?room=".$room_id);
exit();
    
    
?>
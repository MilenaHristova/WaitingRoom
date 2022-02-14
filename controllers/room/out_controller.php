<?php

$config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
$base_url = $config['BASE_URL'];
$base_dir = $config['BASE_FOLDER'];
$room_path = $base_url.'/views/room/room.php';

require_once "$base_dir/db/queue.php";

date_default_timezone_set('Europe/Sofia');

$queueModel = new QueueModel();

$id = $_REQUEST["id"];
$room_id = $_REQUEST["room"];

if($id != '' && $room_id != ''){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
	$date = date('Y-m-d H:i:s', time());
    $queueModel->setOutTime($date, $room_id, $id);
	
	$result = $queueModel->getInTime($room_id, $id);
    
	$date_in = new DateTime($result['in_time']);
	$date_out = new DateTime($date);
	$diff = date_diff($date_out, $date_in);
	$minutes = $diff->days * 24 * 60;
	$minutes += $diff->h * 60;
	$minutes += $diff->i;
	
    $result = $queueModel->getAvgTime($room_id);
	$avg_time = $result['avg_time'];
	$passed = $result['passed_people'];
	
	$avg_time = ($avg_time * $passed + $minutes) / ($passed + 1);
	
	$queueModel->setAvgTime($avg_time, $room_id);
}

header("Location: $room_path?room=".$room_id);
exit();
    
    
?>
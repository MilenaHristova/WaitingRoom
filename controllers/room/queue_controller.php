<?php

$config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
$base_dir = $config['BASE_FOLDER'];
$base_url = $config['BASE_URL'];
$room_path = $base_url.'/views/room/room.php';

require_once "$base_dir/db/queue.php";
require_once "$base_dir/db/room.php";

date_default_timezone_set('Europe/Sofia');

$model = new QueueModel();
$roomModel = new RoomModel();

if(isset($_POST["next"])){
    $room_id = $_POST["room_id"];
    $model->updateNext($_POST["room_id"]);
    
    header("Location: $room_path?room=".$room_id);
    exit();
} elseif(isset($_POST['remove_me'])){
	
	$room_id = $_POST["room_id"];
	$student_id = $_POST['user_id'];
	$model->removeFromQueue($room_id, $student_id);
	header("Location: $room_path?room=".$room_id);
	
} elseif(isset($_POST['add_me'])){
	
	$room_id = $_POST["room_id"];
	$student_id = $_POST['user_id'];
	$model->addInQueue($room_id, $student_id);
	header("Location: $room_path?room=".$room_id);
	
} elseif(isset($_POST["delete_room"])){
	$room_id = $_POST["room_id"];
	$roomModel->deleteRoom($room_id);
	header("Location: $base_url/views/lobby.php");
    exit(); 
} elseif(isset($_POST["break"])){
    $room_id = $_POST["room_id"];
    if(!isset($_POST["mins"])){
        header("Location: $room_path?room=".$room_id);
        exit(); 
    
    }
    
    $roomModel->setBreak($room_id, $_POST["mins"]);
    header("Location: $room_path?room=".$room_id);
    exit();
}



?>
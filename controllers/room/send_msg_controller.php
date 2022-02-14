<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
    $base_url = $config['BASE_URL'];
    $base_dir = $config['BASE_FOLDER'];
    $room_path = $base_url.'/views/room/room.php';
    
    require_once $base_dir.'/db/messages.php';
    
	if (session_status() === PHP_SESSION_NONE)
   {
           session_start();
   }
	date_default_timezone_set('Europe/Sofia');
	
	
	$room_id = $_POST['room_id'];
	echo "$room_id";
	$text = $_POST['msg'];
	$author_id = $_SESSION['user_id'];
	$author_name = $_SESSION["name"];
	$send_to = $_POST['send_to'];
	$time = date('Y-m-d h:i:s', time());
	
	$msgModel = new MessagesModel();
    $msgModel->sendMessage($room_id, $text, $author_id, $author_name, $send_to, $time);
    
	header("Location: $room_path?room=$room_id");
	
}
?>
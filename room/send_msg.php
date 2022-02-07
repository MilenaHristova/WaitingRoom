<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
	
	require_once '../connect_db.php';
	$db = Database::getInstance();
    $pdo = $db->getConnection();
	$insert_query = "INSERT INTO messages (room_id, text, author_id, author_name, send_to, time) VALUES (?, ?, ?, ?, ?, ?)";
	$stmt= $pdo->prepare($insert_query);
    $stmt->execute([$room_id, $text, $author_id, $author_name, $send_to, $time]);
	
	header("Location: queue.php?room=$room_id");
	
}
?>
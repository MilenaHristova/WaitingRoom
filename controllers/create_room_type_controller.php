<?php
    $config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
    $base_dir = $config['BASE_FOLDER'];
    $base_url = $config['BASE_URL'];
    $room_path = $base_dir.'/views/room/room.php';
	require_once "$base_dir/db/room.php";

	if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
            
    if(!isset($_SESSION['user_id']) | !isset($_SESSION['user_role']) | $_SESSION['user_role'] != 2){
        header("Location: $base_url/views/lobby.php");
        exit();
    }
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = $_POST['name'];
		$avg_time = $_POST['avg_time'];
		
		if(empty($name)){
			$error = "Името е задължитерно поле";
			header("Location: $base_url/views/create_room_type.php?errors=$error");
			exit();
		}
		
		if(empty($avg_time)){
			$avg_time = NULL;
		}
        
		$roomModel = new RoomModel();
		$roomModel->insertRoomType($name, $avg_time);
		
		header("Location: $base_url/views/create_room.php");
	}
?>
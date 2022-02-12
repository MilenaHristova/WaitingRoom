<?php
	require '../connect_db.php';
	if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
            
    if(!isset($_SESSION['user_id']) | !isset($_SESSION['user_role']) |           $_SESSION['user_role'] != 2){
        header("Location: ../lobby/lobby.php");
        exit();
    }
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = $_POST['name'];
		$avg_time = $_POST['avg_time'];
		
		if(empty($name)){
			$error = "Името е задължитерно поле";
			header("Location: create_room_type_form.php?errors=$error");
			exit();
		}
		
		if(empty($avg_time)){
			$avg_time = NULL;
		}
		
		$db = Database::getInstance();
        $pdo = $db->getConnection();
		
		$sql = 'INSERT INTO room_type(name, avg_time) VALUES (?,?)';
		$stmt= $pdo->prepare($sql);
        $stmt->execute([$name, $avg_time]);
		
		header("Location: create_room_form.php");
	}
?>
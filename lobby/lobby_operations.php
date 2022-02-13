<?php
 if (session_status() === PHP_SESSION_NONE)
   {
           session_start();
   }
require_once('../connect_db.php');

function getAllRooms(){
	$db = Database::getInstance();
	$pdo = $db->getConnection();
	
	$query = 'SELECT name, description, room_id FROM rooms';
	$stmt = $pdo->query($query);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	return $rows;
}

function searchRooms($str){
	$db = Database::getInstance();
	$pdo = $db->getConnection();
	
	$query = 'SELECT name, description, room_id FROM rooms WHERE name LIKE ?';
	$stmt= $pdo->prepare($query);
    $stmt->execute(['%'.$str.'%']);
	//$stmt = $pdo->query($query);
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	return $rows;
}

if(isset($_POST['exit'])){
	session_unset();
	header("Location: ../lobby/lobby.php");
	exit();
} 
else if (isset($_POST['search_rooms'])){
	$str = $_POST['key_word'];
	$_SESSION['rooms'] = searchRooms($str);
	header("Location: ../lobby/lobby.php?search=TRUE");
	exit();
}
?>
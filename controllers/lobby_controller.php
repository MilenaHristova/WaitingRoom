<?php
 if (session_status() === PHP_SESSION_NONE)
   {
           session_start();
   }

$config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
$base_dir = $config['BASE_FOLDER'];
$base_url = $config['BASE_URL'];
            
require_once "$base_dir/db/lobby.php";


function getAllRooms(){
    $model = new LobbyModel();
    return $model->getAllRooms();
}


if(isset($_POST['exit'])){
	session_unset();
	header("Location: $base_url/views/lobby.php");
	exit();
} 
else if (isset($_POST['search_rooms'])){
	$str = $_POST['key_word'];
    $model = new LobbyModel();
	$_SESSION['rooms'] = $model->searchRooms($str);
	header("Location: $base_url/views/lobby.php?search=TRUE");
	exit();
}
?>
<?php
echo "PAGE";
$config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
$base_dir = $config['BASE_FOLDER'];
$base_url = $config['BASE_URL'];

$room_path = $base_url.'/views/room/room.php';
$lobby_path = $base_url.'/views/lobby.php';

require_once "$base_dir/db/queue.php";
require_once "$base_dir/db/room.php";

$queueModel = new QueueModel();
$roomModel = new RoomModel();

if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}

$id = $_POST["student_id"];
$room_id = $_POST["room_id"];

if(isset($_POST["invite_temp"]) | isset($_POST["invite"])){
    echo $_POST["student_id"];
    if(!isset($_SESSION['user_role']) | $_SESSION['user_role'] == 1){
        header("Location: $lobby_path");
        exit();
    } 
    
    if(isset($_POST["invite_temp"])){
        $waiting = 1;
    } else {
        $waiting = 0;
    }
    
    $queueModel->invite($room_id, $id, $waiting);
    
    header("Location: $room_path?room=".$room_id);
    exit();
} elseif(isset($_POST["invite_all"]) | isset($_POST["invite_all_temp"])) {
    if(!isset($_SESSION['user_role']) | $_SESSION['user_role'] == 1){
        header("Location: ".$lobby_path);
        exit();
    } 

    if(isset($_POST["invite_all_temp"])){
        $waiting = 1;
    } else {
        $waiting = 0;
    }
    
    $queueModel->inviteAll($room_id, $waiting);
    
    header("Location: $room_path?room=".$room_id);
    exit();
} elseif(isset($_POST['make_moderator'])){
	$roomModel->setModerator($room_id, $id);

	header("Location: $room_path?room=".$room_id);
    exit();
}





?>
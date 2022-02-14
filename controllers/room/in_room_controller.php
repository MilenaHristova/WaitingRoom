<?php
$config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
$base_dir = $config['BASE_FOLDER'];
$base_url = $config['BASE_URL'];
$room_path = $base_dir.'/views/room/room.php';

require_once "$base_dir/db/queue.php";

if(isset($_POST['remove_all']) || isset($_POST['return_all'])){
    $room_id = $_POST['room_id'];
    
    if(isset($_POST['remove_all'])){
        $waiting = 0;
    } else {
        $waiting = 1;
    }
    
    $model = new QueueModel();
    
    $model->remove_all_from_room($room_id, $waiting);
    header("Location: $base_url/views/room/room.php?room=".$room_id);
    exit();
}

?>
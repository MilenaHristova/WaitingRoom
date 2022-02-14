<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<meta http-equiv="refresh" content="10;URL='room.php?room=<?php echo $_REQUEST['room']?>'">
<title> Чакалня </title>
    <link rel="stylesheet" href="../styles/room.css">
	<link rel="stylesheet" href="../styles/common.css">	
</head>

<body>
    <?php
    $config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
    $base_dir = $config['BASE_FOLDER'];
    $base_url = $config['BASE_URL'];
    
    require_once '../../db/users.php';
    require_once '../../db/room.php';
    require_once '../../db/queue.php';
    require_once '../../db/messages.php';

    if (session_status() === PHP_SESSION_NONE)
    {
        session_start();
    }
   
    if(!isset($_REQUEST['room']))
    {
        echo '<p>Стаята не е намерена.</p>';
        header("Location: $base_dir\views\lobby.php");
        exit();
    }
      
    date_default_timezone_set('Europe/Sofia');

    if(!isset($_SESSION['user_id']))
    {
        echo '<p>Моля влезте в акаунта си.</p>';
        header("Location: $base_dir\views\login.php?room_id={$_REQUEST['room']}");
        exit();
    }
    
    $queueModel = new QueueModel();
    $roomModel = new RoomModel();
    
    $user_role = $_SESSION['user_role'];
    $user_id = $_SESSION['user_id'];
       
    $room_id = $_REQUEST['room'];
    $descr = $roomModel->loadRoomDescr($room_id);
    
    $is_creator = $roomModel->checkIfCreator($room_id, $user_id);
	$is_moderator = $roomModel->checkIfModerator($room_id, $user_id);

    $students = $queueModel->loadQueue($room_id, 30);
    
    $next_team = $queueModel->getNext($room_id);
    $next_fn = $next_team == FALSE ? FALSE : implode(', ', $next_team);
    
    $in_room = $queueModel->getInRoom($room_id);
    $is_next = FALSE;
    if($in_room){
        foreach($in_room as $s){
            if($s["id"] == $user_id){
                $is_next = TRUE;
            }
        }
    }
    
      
    $break_until = $roomModel->getBreak($room_id);
    $text = '';
    
    if(!($break_until == FALSE | strtotime($break_until) - time() <= 0))
    {
        $panel_visibility = 'visible';
        $date = new DateTime($break_until);
        $break_until = $date->format("H:i");
        $text = 'Почивка до '.$break_until;
    } elseif($user_role == 1 && $is_next == TRUE){
        $panel_visibility = 'visible';
        $text = 'Твой ред е!';
        if($descr["url"] != null){
            $text = $text.' url: <a href="'.$descr["url"].'">'.$descr["url"].'</a> ';
        }
        if($descr["meeting_password"] != null){
            $text = $text.'парола:'.$descr["meeting_password"];
        }
    } else {
        $panel_visibility = 'collapse';
    }
           
    ?>
    <header>
        <form method="get" action="<?php echo "../lobby.php" ?>">
            <button  type="submit" class="header_button">Назад</button>
        </form>
		<?php if($roomModel->checkIfCreator($room_id, $user_id)):?>
			<form method="post" action="<?php echo "../../controllers/room/queue_controller.php" ?>">
				<input type="hidden" name="room_id" value=<?php echo $room_id;?>>
				<input type="submit" class="header_button" name="delete_room" value="Изтрий стаята">
			</form>
		<?php endif;?>
        <p><?php echo $descr["name"] ?></p>
        <?php if ($descr["description"] != ''): ?> 
        <p class="descr">(<?php echo $descr["description"] ?>)</p>  
        <?php endif;?>
    </header>
    <!--style="visibility:<?php echo $panel_visibility ?>"-->
    
    <div class="turn-or-break-message" style="visibility:<?php echo $panel_visibility ?>">
            <?php echo $text; ?>
    </div>
    <?php 
    
    if($is_creator || $is_moderator){
        include($base_dir."/views/room/admin.php");
        include($base_dir."/views/room/queue_admin.php");
        include($base_dir."/views/room/in_room.php");
    } else {
        include($base_dir."/views/room/queue.php");
    }
    
    ?>
    
    <div class="side-panel">
        <?php include($base_dir."/views/room/messages.php") ?>
    </div>
    
</body>
</html>

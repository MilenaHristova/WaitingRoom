<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<!--<meta http-equiv="refresh" content="10;URL='room.php?room=<?php echo $_REQUEST['room']?>'">-->
<title> Чакалня </title>
    <link rel="stylesheet" href="room.css">
	<link rel="stylesheet" href="../common.css">	
</head>

<body>
    <?php
    require_once '../connect_db.php';
    include_once 'queue_operations.php';

    if (session_status() === PHP_SESSION_NONE)
    {
        session_start();
    }
   
    if(!isset($_REQUEST['room']))
    {
        echo '<p>Стаята не е намерена.</p>';
        header("Location: ../lobby/lobby.php");
        exit();
    }
      
    date_default_timezone_set('Europe/Sofia');

    if(!isset($_SESSION['user_id']))
    {
        echo '<p>Моля влезте в акаунта си.</p>';
        header("Location: ../login/login.php?room_id={$_REQUEST['room']}");
        exit();
    }
    
    $user_role = $_SESSION['user_role'];
    $user_id = $_SESSION['user_id'];
	
    $room_id = $_REQUEST['room'];
    $descr = loadRoomDescr($room_id);
	
	$is_creator = checkIfCreator($room_id, $user_id);
	$is_moderator = checkIfModerator($room_id, $user_id);
	
    $students = loadQueue($room_id, 10);   
    $next_team = getNext($room_id);
    $next_fn = $next_team == FALSE ? FALSE : implode(', ', $next_team);
    
    $in_room = getInRoom($room_id);
    $is_next = FALSE;
    if($in_room){
        foreach($in_room as $s){
            if($s["id"] == $user_id){
                $is_next = TRUE;
            }
        }
    }
    
      
    $break_until = getBreak($room_id);
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
        <form method="get" action="../lobby/lobby.php">
            <button  type="submit" class="header_button">Назад</button>
        </form>
		<?php if(checkIfCreator($room_id, $user_id)):?>
			<form method="post" action="queue_operations.php">
				<input type="hidden" name="room_id" value=<?php echo $room_id;?>>
				<input type="submit" class="header_button" name="delete_room" value="Изтрий стаята">
			</form>
		<?php endif;?>
        <p><?php echo $descr["name"] ?></p>
        <p class="descr">(<?php echo $descr["description"] ?>)</p>   
    </header>
    <!--style="visibility:<?php echo $panel_visibility ?>"-->
    
    <div class="turn-or-break-message" style="visibility:<?php echo $panel_visibility ?>">
            <?php echo $text; ?>
    </div>
    <?php 
    
    if($is_creator || $is_moderator){
        include("admin.php");
        include("queue_admin.php");
        include("list.php");
    } else {
        include("queue.php");
    }
    
    ?>
    
    <div class="side-panel">
        <?php include_once("messages.php") ?>
    </div>
    
</body>
</html>

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
    $students = loadQueue($room_id, 10);
    
    $next_team = getNext($room_id);
    $next_fn = $next_team == FALSE ? FALSE : implode(', ', $next_team);
      
    $break_until = getBreak($room_id);
    $text = '';
    
    if($break_until == FALSE | strtotime($break_until) - time() <= 0){
        $panel_visibility = 'collapse';
    } else{
        $panel_visibility = 'visible';
        $date = new DateTime($break_until);
        $break_until = $date->format("H:i");
        $text = 'Почивка до '.$break_until;
    }
    
    if($user_role == 1 && $next_team != FALSE && in_array($_SESSION["fn"], $next_team)){
        $panel_visibility = 'visible';
        $text = 'Твой ред е!';
        if($descr["url"] != null){
            $text = $text.'url: '.$descr["url"].' ';
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
        <p><?php echo $descr["name"] ?></p>
        <p class="descr">(<?php echo $descr["description"] ?>)</p>   
    </header>
    <!--style="visibility:<?php echo $panel_visibility ?>"-->
    
    <div class="panel">
            <?php echo $text; ?>
        </div>
        
        <?php 
        if($user_role == 2){
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

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<!--<meta http-equiv="refresh" content="10;URL='room.php?room=<?php echo $_REQUEST['room']?>'">-->
<title> Чакалня </title>
    <link rel="stylesheet" href="room.css">
	
	
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
    
    if($user_role != 1){
        header("Location: ../lobby/lobby.php");
        exit();
    }

           
    $room_id = $_REQUEST['room'];
    $descr = loadRoomDescr($room_id);
    $students = loadQueue($room_id, 10);
    $next_team = getNext($room_id);
    $next_fn = $next_team == FALSE ? FALSE : implode(', ', $next_team);

    $user_time = getStudentTime($user_id, $room_id);
    
    $now = time();
    $time = strtotime($user_time);
    $dif = round(($time - $now) / 60);
      
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
    <div class="container">
        <button><a href='../lobby/lobby.php'>Назад</a></button>
        <div class="title">
            <h1><?php echo $descr["name"] ?></h1>
            
            <div class="title">  
                <p><?php echo $descr["description"] ?></p>
            </div> 
        </div>
        
        <div class="panel" style="visibility:<?php echo $panel_visibility ?>">
            <?php echo $text; ?>
        </div>
        
        <div class="left-panel">
            <h2>Опашка чакащи</h2>
            <div class="header">
                <div class="next">
                    <p>Следващият номер:</p>
                    <p><?php echo $next_fn != FALSE ? $next_fn:'Край' ?></p>
                </div>
                <div class="time">
                    <p>Оценено време за чакане:</p>
                    <p><?php echo $dif.' минути'; ?></p>
                </div>
              </div>
            
              <div class="queue">
                 <ul class="list">
                    <?php
                    foreach($students as $student){
                        $str = $student["time"] == null ? $student["fn"] : $student["fn"].'    ('.$student["time"].'ч)';
                        
                        echo '<li>'.$str.'</li>';
                    }
                    ?>
                 </ul>
              </div>
        </div>
        
        <div class="side-panel">
            <?php include_once("messages.php") ?>
        </div>
    </div>  
</body>

</html>

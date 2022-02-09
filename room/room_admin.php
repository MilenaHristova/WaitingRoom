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
    
    if($user_role != 2){
        header("Location: ../lobby/lobby.php");
        exit();
    }
       
    $room_id = $_REQUEST['room'];
    $descr = loadRoomDescr($room_id);
    $students = loadQueue($room_id, 10);
    $students_in_room = getInRoom($room_id);
    $next_team = getNext($room_id);
    $next_fn = $next_team == FALSE ? FALSE : implode(', ', $next_team);
      
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
        
        <div class="break-div">
                <form action="queue_operations.php" method="post">
                    Почивка: <input type="number" name="mins" id="mins"> минути
                    <input type="submit" name="break" id="break-btn" value="Ок"> 
                </form>
        </div>
        
        <div class="left-panel-admin">
            <h2>Опашка чакащи</h2>
            <div class="header">
                <div class="next">
                    <p>Следващият номер:</p>
                    <p><?php echo $next_fn != FALSE ? $next_fn:'Край' ?></p>
                </div>
                
                <div class="next-btn-div">
                    <form action="queue_operations.php" method="post">
                        <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                        <input type="submit" name="next" id="next" value="Следващ">
                    </form>
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
            
            <?php include("search.php") ?>
            
        </div>
        
        <div class="center">
            <h2>В стаята сега:</h2>
            <div class="in-room">
                 <ol>
                     
                    <?php
                    if($students_in_room){
                        foreach($students_in_room as $student){
                        $str = $student["fn"].' ('.$student["name"].')';
                        
                        echo '<li>'.$str."  <button><a href=\"out.php?id={$student['id']}&room={$room_id}\">Излязъл</a></button></li>";
                        }
                    }
                    
                    ?>
                 </ol>
              </div>
        </div>
        
        
        <div class="side-panel">
            <?php include_once("messages.php") ?>
        </div>
    
    </div>
</body>
</html>

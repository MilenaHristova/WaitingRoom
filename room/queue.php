<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<meta http-equiv="refresh" content="60;URL='queue.php?room=<?php echo $_REQUEST['room']?>'">
<title> Чакалня </title>
    <link rel="stylesheet" href="queue.css">
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

           
    $room_id = $_REQUEST['room'];
    $descr = loadRoomDescr($room_id);
    $students = loadQueue($room_id, 10);
    $next_team = getNext($room_id);
    $next_fn = $next_team == FALSE ? FALSE : implode(', ', $next_team);

    $user_role = $_SESSION['user_role'];
    $user_id = $_SESSION['user_id'];
      
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
    
    if($user_role == 1 && in_array($_SESSION["fn"], $next_team)){
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
                <?php if($user_role == 1): ?>
                <div class="time">
                    <p>Оценено време за чакане:</p>
                    <p><?php echo $dif.' минути'; ?></p>
                </div>
                <?php elseif($user_role == 2): ?>
                <div class="next-btn-div">
                    <form action="queue_operations.php" method="post">
                        <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                        <input type="submit" name="next" id="next" value="Следващ">
                        <div id="break-div">
                            Почивка: <input type="number" name="mins" id="mins"> минути
                            <input type="submit" name="break" id="break-btn" value="Ок">    
                        </div>
                    </form>
                </div>
                <?php endif; ?>
              </div>
            
              <div class="queue">
                 <ul class="list">
                    <?php
                    foreach($students as $student){
                        echo '<li>'.$student["name"].', '.$student["fn"].'</li>';
                    }
                    ?>
                 </ul>
              </div>
            
            <?php if($user_role == 2): ?>
            <div class="search">
                <form action="" method="post">
                    <label for="fn">Факултетен номер</label>
                    <input type="text" name="fn" id="fn">
                    <input type="submit" name="search" value="Търсене">
                </form>
                <div class="search-res-div">
                    <?php
                    if(isset($_POST["search"])){
                        $fn = $_POST["fn"];
                        $res = searchByFn($fn);
                        if($res == FALSE){
                            echo '<p class="search-res">Не е намерен резултат.</p>';
                        } else { ?>
                            <p class="search-res"><?php echo $res["name"] ?></p>
                            <form action="search.php" method="post">
                                <input type="hidden" name="student_id" value="<?php echo $res["user_id"] ?>">
                                <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                                <input type="submit" name="invite_temp" value="Покани временно">
                                <input type="submit" name="invite" value="Покани постоянно">
                            </form>
                    <?php
                        }
                    }
                    
                    ?>

                </div>
            </div>
            <?php endif; ?>
            
        </div>
        <div class="center"></div>
        <div class="side-panel">
            <div class="chat">
                <p>Съобщения</p>
                <div class="msg">
                    <p class="msg-text">Message 1</p>
                    <p class="author">Author</p>
                </div>
                <div class="msg">
                    <p class="msg-text">Message 2</p>
                    <p class="author">Author</p>
                </div>
            </div>
            <div class="add-msg">
                <form action="db.php" method="post">
                    <textarea name="msg"></textarea>
                    <input type="radio" name="send_to" id="all" value="all">
                    <label for="all">до всички</label>
                    <input type="radio" name="send_to" id="teacher" value="teacher">
                    <label for="teacher">до преподавател</label>
                    <input type="submit" class="msg-submit-btn" value="Изпрати">
                </form>
            </div>
            
       </div>
    </div>
    
    

</body>

</html>

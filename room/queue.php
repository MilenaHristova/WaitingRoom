<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<meta http-equiv="refresh" content="10;URL='queue.php?room=<?php echo $_REQUEST['room']?>'">
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
    
    if($break_until == FALSE | strtotime($break_until) - time() <= 0){
        $break_vis = 'hidden';
    } else{
        $break_vis = 'visible';
    }
    
    if($user_role == 1 && $next_team != FALSE && in_array($_SESSION["fn"], $next_team)){
        $panel_visibility = 'visible';
    } else {
        $panel_visibility = 'hidden';
    }
           
    
    ?>
    <div class="container">
        <div class="navbar">
            <button><a href='../lobby/lobby.php'>Назад</a></button>
            <div class="title">
                <h1><?php echo $descr["name"] ?></h1>
                <p><?php echo $descr["description"] ?></p>
            </div> 
        </div>
        <div class="panel" style="visibility:<?php echo $panel_visibility ?>">Твой ред е! <?php echo $descr["url"] != null ? 'url: '.$descr["url"] : '' ?> <?php echo $descr["meeting_password"] != null ? 'парола:'.$descr["meeting_password"] : ''?></div>
        
        <div class="break-panel" style="visibility:<?php echo $break_vis ?>">
            <?php echo 'Почивка до '.$break_until; ?>
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
                            <input type="number" name="mins" id="mins"> минути
                            <input type="submit" name="break" id="break-btn" value="Почивка">    
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
				<div class="messages id="messages">
				<div>
				<?php
					$db = Database::getInstance();
					$pdo = $db->getConnection();
					$query = 'SELECT * FROM messages WHERE room_id = '.$room_id. ' ORDER BY time';
					$statements = $pdo->query($query);
					$messages = $statements->fetchAll(PDO::FETCH_ASSOC);
					if($messages){
						foreach($messages as $msg){
							if ($msg['send_to'] == 0 || $msg['author_id'] == $user_id || ($msg['send_to'] == 1 && $user_role == 2)){
								if($msg['author_id'] == $user_id){
									$msg_class="msg_from";
								} else{
									$msg_class="msg_to";
								}
								
								echo "
									<div class=$msg_class>
										<span class=\"author\">{$msg['author_name']}</span>
										<span class=\"msg_text\">{$msg['text']}</span>
										<span class=\"msg_time\">{$msg['time']}</span>
									</div>
								";
							}
						}
					}

				?>
				
               </div>
			   </div>
            </div>
            <div class="add-msg">
                <form action="send_msg.php" method="post">
                     <input type="hidden" name="room_id" value="<?php echo $room_id?>">
                    <textarea name="msg"></textarea>
                    <input type="radio" checked="checked" name="send_to" id="all" value="0">
                    <label for="all">до всички</label>
                    <input type="radio" name="send_to" id="teacher" value="1">
                    <label for="teacher">до преподавател</label>
                    <input type="submit" class="msg-submit-btn" value="Изпрати">
                </form>
            </div>
            
       </div>
    </div>
    
    

</body>

</html>

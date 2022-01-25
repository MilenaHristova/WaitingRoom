<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<title> Чакалня </title>
    <link rel="stylesheet" href="queue.css">
</head>

<body>
    <?php
        require_once 'connect_db.php';
        include_once 'queue_operations.php';
    
        session_start();
    
        if(!isset($_REQUEST['room']))
        {
            echo '<p>Стаята не е намерена.</p>';
            header("Location: lobby.php");
            exit();
        }
           
        $room_id = $_REQUEST['room'];
        $descr = loadRoomDescr($room_id);
        $students = loadQueue($room_id, 10);
        
        if(!isset($_SESSION["current_place"])){
            $_SESSION["current_place"] = 1;
            getNext($room_id);
        }
    
        $current_fn = $_SESSION["current_fn"];
        
    
        //$user_role = $_SESSION['user_role'];
        $user_role = 2;
           
    
    ?>
    <div class="container">
        <div class="navbar">
            <button><a href=#>Изход</a></button>
            <div class="title">
                <h1><?php echo $descr["name"] ?></h1>
                <p><?php echo $descr["description"] ?></p>
            </div> 
        </div>
        <?php if(isset($_REQUEST['room'])) : ?>
          <div class="panel"><?php echo $_REQUEST['room'] ?></div>
        <?php endif; ?>
        
        <div class="left-panel">
            <h2>Опашка чакащи</h2>
            <div class="header">
                <div class="next">
                    <p>Следващият номер:</p>
                    <p><?php echo $current_fn ?></p>
                </div>
                <?php if($user_role == 1): ?>
                <div class="time">
                    <p>Средно чакане:</p>
                    <p>25 минути</p>
                </div>
                <?php elseif($user_role == 2): ?>
                <div class="next-btn-div">
                    <form action="queue_operations.php" method="post">
                        <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                        <input type="submit" name="next" value="Следващ">
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
                <form action="#" method="get">
                    <label for="fn">Факултетен номер</label>
                    <input type="text" name="fn" id="fn">
                    <input type="submit" value="Търсене">
                </form>
                <div class="search-res-div">
                    <p class="search-res">Петър Петров 9999</p>
                    <button><a href=#>Покани временно</a></button>
                    <button><a href=#>Покани постоянно</a></button>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
        <div class="center">
            <h2>Временна опашка</h2>
            <div class="header">
                <div class="next">
                    <p>Следващият номер:</p>
                    <p>8999</p>
                </div>
            </div>
            
            <div class="queue">
                <ul class="list">
                    <li>First</li>
                    <li>Second</li>
                    <li>Third</li>
                </ul>
            </div> 
        </div>
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
<! DOCTYPE html>
<?php
$students_in_room = $queueModel->getInRoom($room_id);
?>

<html>
    <div class="left-panel-admin">
        <h2>Опашка чакащи</h2>
        <div class="header">
            <div class="next next_admin">
                <p>Следващият номер:</p>
                <p><?php echo $next_fn != FALSE ? $next_fn:'Край' ?></p>
            </div>
            
            <form action="../../controllers/room/queue_controller.php" method="post">
                <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                <input type="submit" name="next" class="btn_next" value="Следващ">
            </form>
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
</html>
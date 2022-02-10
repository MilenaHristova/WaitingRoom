<! DOCTYPE html>
<?php
$students_in_room = getInRoom($room_id);
?>

<html>
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
    </div>
</html>
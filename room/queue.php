<! DOCTYPE html>
<html>
    <?php
    
    $user_time = getStudentTime($user_id, $room_id);
    $time = date("H:i", strtotime($user_time));
    
    ?>
    <div class="left-panel">
        <h2>Опашка чакащи</h2>
        <div class="header">
            <div class="next">
                <p>Следващият номер:</p>
                <p><?php echo $next_fn != FALSE ? $next_fn:'Край' ?></p>
            </div>
            <div class="time">
                <p>Час по график:</p>
                <p><?php echo $time.' ч'; ?></p>
            </div>
            <div class="time">
                <p>Оценено време за чакане:</p>
                <p><?php echo '15 минути'; ?></p>
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
<! DOCTYPE html>
<html>
    <?php
    
	$student_in_queue = checkIfInQueue($room_id, $user_id);
	if($student_in_queue){
		$user_time = getStudentTime($user_id, $room_id);
		$time = date("H:i", strtotime($user_time));
		$waiting_time = getEstimatedWaitingTime($room_id, $user_id);
	}
	
    
    ?>
    <div class="left-panel">
        <h2>Опашка чакащи</h2>
        <div class="header">
            <div class="next">
                <p>Следващият номер:</p>
                <p><?php echo $next_fn != FALSE ? $next_fn:'Край' ?></p>
            </div>
			<?php if ($student_in_queue):?>
            <div class="time">
                <p>Час по график:</p>
                <p><?php echo $time.' ч'; ?></p>
            </div>
            <div class="time">
                <p>Оценено време за чакане:</p>
                <p><?php echo $waiting_time.' минути'; ?></p>
            </div>
			<?php endif; ?>
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
<! DOCTYPE html>
<html>
    <?php

	$student_in_queue = $queueModel->checkIfInQueue($room_id, $user_id);
	if($student_in_queue){
		$user_time = $queueModel->getStudentTime($user_id, $room_id);
		if($user_time == FALSE){
			$time = FALSE;
		}
		else{
			$time = date("H:i", strtotime($user_time));
		}
		$waiting_time = $queueModel->getEstimatedWaitingTime($room_id, $user_id);
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
			<?php if ($time != FALSE):?>
            <div class="time">
                <p>Час по график:</p>
                <p><?php echo $time.' ч'; ?></p>
            </div>
			<?php endif; ?>
            <div class="time">
                <p>Оценено време за чакане:</p>
                <p><?php echo $waiting_time.' минути'; ?></p>
			</div>
			
			<div class="remove_me">
				<form action="../../controllers/room/queue_controller.php" method="post">
                    <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
					<input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                    <input type="submit" class="blue_button" name="remove_me" id="remove_me" value="Излез от опашката">
                </form>
			</div>
		</div>	
            <?php else: ?>
		
			<div class="add_me">
				<form action="../../controllers/room/queue_controller.php" method="post">
                    <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
					<input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                    <input type="submit" class="blue_button" name="add_me" id="add_me" value="Нареди се">
                </form>
			</div>
		</div>
			<?php endif; ?>
          
        
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
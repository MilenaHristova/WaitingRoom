<!DOCTYPE html>
<html>
    <div class="chat">
        <h2>Съобщения</h2>
        <div class="messages" id="messages">
            <div>
                <?php
                
                $msgModel = new MessagesModel();
                $messages = $msgModel->getMessages($room_id);
                
                if($messages){
                    foreach($messages as $msg){
                        
			 	        if ($msg['send_to'] == 0 || $msg['author_id'] == $user_id || ($msg['send_to']    == 1 && $user_role == 2)){
			 	           if($msg['author_id'] == $user_id){
			 	               $msg_class="msg_from";
			 	            } else{
			 	               $msg_class="msg_to";
			 	            }
                            
                            $time = date("H:i", strtotime($msg['time']));
								
				            echo "
				            <div class=$msg_class>
				            <span class=\"msg_text\">{$msg['text']}</span>
                            <div class=\"msg_div\"><span class=\"author\">{$msg['author_name']}</span>
				            <span class=\"msg_time\">{$time}</span></div>
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
        <form action="../../controllers/room/send_msg_controller.php" method="post">
             <input type="hidden" name="room_id" value="<?php echo $room_id?>">
            <textarea name="msg"></textarea>
            <input type="radio" checked="checked" name="send_to" id="all" value="0">
            <label for="all">до всички</label>
            <input type="radio" name="send_to" id="teacher" value="1">
            <label for="teacher">до преподавател</label>
            <input type="submit" class="blue_button send_button" value="Изпрати">
        </form>
    </div>
</html>
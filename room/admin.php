<! DOCTYPE html>
<html>
    <div class="admin-panel">
        <div class="search">
        <form action="" method="post" class="search-form">
            <input type="text" name="fn" id="fn" class="field" placeholder="Факултетен номер">
            <input type="submit" name="search" value="Търсене" class="blue_button">
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
                        <form action="invite.php" method="post">
                            <input type="hidden" name="student_id" value="<?php echo $res["user_id"] ?>">
                            <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                            <input type="submit" class="blue_button" name="invite_temp" value="Покани временно">
                            <input type="submit" class="blue_button" name="invite" value="Покани постоянно">
							<?php if ($is_creator):?>
							<input type="submit" class="blue_button" name="make_moderator" value="Покани за помощник">
							<?php endif;?>
                        </form>
            <?php
                    }
                        
                }
                    
            ?>

        </div>
      </div>
        <div class="break-div">
            <form action="queue_operations.php" method="post">
                Почивка: 
				<input type="hidden" name="room_id" value="<?php echo $room_id ?>">
				<input type="number" name="mins" id="mins" class="field" placeholder="минути"> 
                <input type="submit" name="break" id="break-btn" class="blue_button" value="Ок"> 
            </form>
        </div>
</div> 
<div class="invite-div">
    
    <form action="invite.php" method="post">
        Покани всички:
        <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
        <input type="submit" name="invite_all" value="Постоянно" class="blue_button">
        <input type="submit" name="invite_all_temp" value="Временно" class="blue_button">
    </form>
</div>
    

</html>
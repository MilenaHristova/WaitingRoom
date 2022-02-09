<! DOCTYPE html>
<html>
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
                        <form action="invite.php" method="post">
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
</html>
<html>
    <div class="center">
            <h2>В стаята сега:</h2>
            <div class="in-room">
                <table>
                    <?php
                    if($students_in_room){
                        foreach($students_in_room as $student){
                            echo '<tr>';
                            $str = $student["fn"].' ('.$student["name"].')';
                            echo "<td>$str</td>";
                            echo "<td><button class=\"blue_button\"><a href=\"../../controllers/room/out_controller.php?id={$student['id']}&room={$room_id}\">Излязъл</a></button></td>";
                        }
                    }
                    
                    ?>
                    
                </table>
              </div>
            <div>
                <form action="../../controllers/room/in_room_controller.php" method="post">
                    <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                    <input type="submit" name="remove_all" value="Изгони всички" class="blue_button">
                    <input type="submit" name="return_all" value="Върни всички на опашката" class="blue_button">
                </form>
            </div>
    </div>
</html>
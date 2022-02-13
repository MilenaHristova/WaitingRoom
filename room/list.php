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
                            echo "<td><button class=\"blue_button\"><a href=\"out.php?id={$student['id']}&room={$room_id}\">Излязъл</a></button></td>";
                        }
                    }
                    
                    ?>
                    
                </table>
              </div>
            <div>
                <form action="" method="post">
                    <input type="hidden" name="room_id" value="<?php echo $room_id ?>">
                    <input type="submit" name="remove_all" value="Изгони всички" class="blue_button">
                    <input type="submit" name="return_all" value="Върни всички на опашката" class="blue_button">
                </form>
            </div>
    </div>
</html>

<?php

if(isset($_POST['remove_all']) || isset($_POST['return_all'])){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $room_id = $_POST['room_id'];
    
    if(isset($_POST['remove_all'])){
        $sql = 'UPDATE room_student SET in_room = FALSE WHERE in_room = TRUE AND room_id = ?;';
    } else {
        $sql = 'UPDATE room_student SET in_room = FALSE, waiting = TRUE WHERE in_room = TRUE AND room_id = ?;';
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$room_id]);  
}

?>
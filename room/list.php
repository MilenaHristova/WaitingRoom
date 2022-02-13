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
        </div>
</html>
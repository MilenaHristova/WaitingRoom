<html>
    <div class="center">
            <h2>В стаята сега:</h2>
            <div class="in-room">
                 <ol>
                     
                    <?php
                    if($students_in_room){
                        foreach($students_in_room as $student){
                        $str = $student["fn"].' ('.$student["name"].')';
                        
                        echo '<li>'.$str."  <button><a href=\"out.php?id={$student['id']}&room={$room_id}\">Излязъл</a></button></li>";
                        }
                    }
                    
                    ?>
                 </ol>
              </div>
        </div>
</html>
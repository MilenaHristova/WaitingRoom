<html>
    <body>
        <p>
            <?php
            
                require 'connect_db.php';
            
                function random_filename($length, $directory, $extension) {    
                    do {
                        $key = '';
                        $keys = array_merge(range(0, 9), range('a', 'z'));
                
                        for ($i = 0; $i < $length; $i++) {
                            $key .= $keys[array_rand($keys)];
                        }
                    } while (file_exists($directory.$key.'.'.$extension));

                    return $directory.$key.'.'.$extension;
                }
            
                $title = '';
                $descr = '';
                $url = '';
                $passwd = '';
                $userid = 1;
            
                $target_file = null;
                $target_dir = "uploads/";
                
                if(!isset($_POST["submit"])){
                    exit();
                }
                   
                $title = $_POST["title"];
                $descr = $_POST["descr"];
                $url = $_POST["url"];
                $passwd = $_POST["password"];
                   
                if(isset($_FILES["students"])){
                    $file = $target_dir . basename($_FILES["students"]["name"]);
                    $file_type = strtolower(pathinfo($file,PATHINFO_EXTENSION));
                    $target_file = random_filename(10, $target_dir, $file_type);
                    $ok = 1;
                        
                    if ($_FILES["students"]["size"] > 500000) {
                        echo "Файлът е прекалено голям.";
                        $ok = 0;
                    }
                        
                    if($file_type != "csv") {
                        echo "Само csv файлове се подържат.";
                        $ok = 0;
                    } 
                        
                    if ($ok == 0 || !move_uploaded_file($_FILES["students"]["tmp_name"], $target_file)) {
                        echo "Неуспешно качване на файл.";
                    } 
                }    
            
                
                try{
                    $query = 'INSERT INTO rooms(creator_id, moderator_id, name, description, meeting_password, url) values('.$pdo->quote($userid).','.$pdo->quote($userid).','.$pdo->quote($title).','.$pdo->quote($descr).','.$pdo->quote($passwd).','.$pdo->quote($url).')';
                    
                    $pdo->exec($query);
                    $room_id = $pdo->lastInsertId();
                    $first_fn = null;
                    
                    if($target_file != null){
                        $file = fopen($target_file, 'r');
                        if($file){
                            $inserts = array();
                            $place = 1;
                            while (($row = fgetcsv($file)) !== FALSE){
                                $fn = $row[0];
                                if($fn == ''){
                                    continue;
                                }
                                
                                if($first_fn == null){
                                    $first_fn = $fn;
                                }
                                
                                $id_query = 'SELECT user_id FROM users WHERE faculty_number = '.$fn;
                                $st = $pdo->query($id_query);
                                $res = $st->fetch(PDO::FETCH_ASSOC);
                                $id = $res["user_id"];
                                if($id == null){
                                    continue;
                                }
                                
                                array_push($inserts, '('.$room_id.','.$id.','.$place.')');
                                $place = $place + 1;
                            }
                            
                            if(!empty($inserts)){
                                $insert_str = implode(', ', $inserts);
                                $query = 'INSERT INTO room_student(room_id, student_id, place) VALUES'.$insert_str.';';
                                $pdo->exec($query);
                            }
                            
                            if($first_fn != null){
                                $query = 'UPDATE rooms SET next_fn = '.$first_fn.' WHERE room_id = '.$room_id;
                                $pdo->exec($query);
                            }
                        }  
                    }
                    
                    header("Location: queue.php?room=".$room_id);
                    exit();
                    
                } catch(PDOException $e){
                    echo 'failed';
                    echo $e->getMessage();
                }
            ?>
        </p>
    </body>
</html>

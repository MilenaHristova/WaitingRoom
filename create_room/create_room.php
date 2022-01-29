<html>
    <body>
        <p>
            <?php
            
            require '../connect_db.php';
            
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }
            
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
            
            function uploadFile($target_dir){
                $file = $target_dir.basename($_FILES["students"]["name"]);
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
                        
                if ($ok == 0 || !move_uploaded_file($_FILES["students"]["tmp_name"], $target_file)){
                    echo "Неуспешно качване на файл.";
                    return null;
                } 
                
                return $target_file;
            }
            
            function insertRoomDetails($pdo){
                $title = $_POST["title"];
                $descr = $_POST["descr"];
                $url = $_POST["url"];
                $passwd = $_POST["password"];
                $userid = $_SESSION["user_id"];
                
                $query = 'INSERT INTO rooms(creator_id, moderator_id, name, description, meeting_password, url) values('.$pdo->quote($userid).','.$pdo->quote($userid).','.$pdo->quote($title).','.$pdo->quote($descr).','.$pdo->quote($passwd).','.$pdo->quote($url).')';
                    
                $pdo->exec($query);
            }
            
            
            $target_file = null;
            $target_dir = "../uploads/";
                
            if(!isset($_POST["submit"])){
                exit();
            }
                      
            if(isset($_FILES["students"])){
                $target_file = uploadFile($target_dir);
            }   
            
            $db = Database::getInstance();
            $pdo = $db->getConnection();
               
            try{
                insertRoomDetails($pdo);
                $room_id = $pdo->lastInsertId();
                
                $first_fn = null;
                    
                if($target_file != null){
                    $file = fopen($target_file, 'r');
                    
                    if($file){
                        $inserts = array();
                        $teams = array();
                        $team_num = 1;
                        $place = 1;
                        
                        while (($row = fgetcsv($file)) !== FALSE){
                            $fn = $row[0];
                            if($fn == ''){
                                continue;
                            }
                            
                            if($row[1] != ''){
                                $t = date_parse_from_format("d/m/Y h:i", $row[1]);
                                $tstamp = mktime($t["hour"], $t["minute"], 0, $t["month"], $t["day"], $t["year"]);
                                $time = date("Y-m-d h:i", $tstamp);
                            }
                            
                            
                            $team_lead = $row[2];
                                
                            if($first_fn == null){
                                $first_fn = $fn;
                            }
                            
                            if($team_lead == $fn){
                                $teams[$fn] = $team_num;
                                $team = $team_num;
                                $team_num += 1;
                            } else {
                                $team = $teams[$team_lead];
                            }
                                
                            $id_query = 'SELECT user_id FROM users WHERE faculty_number = '.$fn;
                            $st = $pdo->query($id_query);
                            $res = $st->fetch(PDO::FETCH_ASSOC);
                            $id = $res["user_id"];
                            if($id == null){
                                continue;
                            }
                                
                            array_push($inserts, '('.$pdo->quote($room_id).','.$pdo->quote($id).','.$pdo->quote($place).','.$pdo->quote($time).','.$pdo->quote($team).')');
                            $place = $place + 1;
                        }
                            
                        if(!empty($inserts)){
                            $insert_str = implode(', ', $inserts);
                            $query = 'INSERT INTO room_student(room_id, student_id, place, time, team) VALUES'.$insert_str.';';
                            $pdo->exec($query);
                        }
                            
                        if($first_fn != null){
                            $query = 'UPDATE rooms SET next_fn = '.$first_fn.' WHERE room_id = '.$room_id;
                            $pdo->exec($query);
                        }
                    }  
                }
                    
                header("Location: ../room/queue.php?room=".$room_id);
                exit();
                    
            } catch(PDOException $e){
                echo 'failed';
                echo $e->getMessage();
            }
            ?>
        </p>
    </body>
</html>
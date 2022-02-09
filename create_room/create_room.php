<html>
    <body>
        <p>
            <?php
            
            require '../connect_db.php';
            
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }
            
            if(!isset($_SESSION['user_id']) | !isset($_SESSION['user_role']) |           $_SESSION['user_role'] != 2){
                header("Location: ../lobby/lobby.php");
                exit();
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
            
            function uploadFile($target_dir, &$form_errors){
                $file = $target_dir.basename($_FILES["students"]["name"]);
                $file_type = strtolower(pathinfo($file,PATHINFO_EXTENSION));
                $target_file = random_filename(10, $target_dir, $file_type);
                $ok = 1;
                        
                if($file_type != "csv") {
                    $form_errors["file_type"] = "Само csv файлове се подържат. ";
                    $ok = 0;
                } elseif ($_FILES["students"]["size"] > 500000) {
                    $form_errors["file_size"] = "Файлът е прекалено голям. ";
                    $ok = 0;
                }
                        
                if ($ok == 0 || !move_uploaded_file($_FILES["students"]["tmp_name"], $target_file)){
                    $form_errors["file"] = "Неуспешно качване на файл.";
                    return null;
                } 
                
                return $target_file;
            }
            
            
            function insertRoomDetails($pdo){
                $title = $_POST["title"];
                $descr = $_POST["descr"];
                $url = $_POST["url"];
                $passwd = $_POST["password"];
                $type = $_POST["type"];
                $userid = $_SESSION["user_id"];
                
                $query = 'INSERT INTO rooms(creator_id, moderator_id, name, description,
                type, meeting_password, url) values(?, ?, ?, ?, ?, ?, ?)';
                
                $stmt= $pdo->prepare($query);
                $stmt->execute([$userid, $userid, $title, $descr, $type, $passwd, $url]);
            }
            
            
            $target_file = null;
            $target_dir = "../uploads/";
                
            if(!isset($_POST["submit"])){
                exit();
            }
            
            $errors = array();
                
            if(!isset($_POST['title']) | $_POST['title'] == ''){
                $errors['title'] = 'Заглавието е задължително поле.';
                
                header("Location: create_room_form.php?form_errors=".urlencode(serialize($errors)));
                exit();
            }
                      
            if(!empty($_FILES) & $_FILES["students"]["size"] > 0){
                $target_file = uploadFile($target_dir, $errors);
                
                if($target_file == null){
                    header("Location: create_room_form.php?form_errors=".urlencode(serialize($errors)));
                    exit();
                }
            }   
            
            $db = Database::getInstance();
            $pdo = $db->getConnection();
               
            try{
                insertRoomDetails($pdo);
                $room_id = $pdo->lastInsertId();
                    
                if($target_file != null){
                    $file = fopen($target_file, 'r');
                    
                    if($file){
                        $inserts = array();
                        $teams = array();
                        $team_num = 1;
                        $insert_sql = 'INSERT INTO room_student(room_id, student_id, time, team) VALUES (?, ?, ?, ?)';
                        
                        while (($row = fgetcsv($file)) !== FALSE){
                            $fn = $row[0];
                            if($fn == ''){
                                continue;
                            }
                            
                            if($row[1] != ''){
                                $t = date_parse_from_format("d/m/Y H:i", $row[1]);
                                $tstamp = mktime($t["hour"], $t["minute"], 0, $t["month"], $t["day"], $t["year"]);
                                $time = date("Y-m-d H:i", $tstamp);
                            }
                            
                            
                            $team_lead = $row[2];
                            
                            if($team_lead == $fn || $team_lead == ''){
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
                                
                            $stmt= $pdo->prepare($insert_sql);
                            $stmt->execute([$room_id, $id, $time, $team]);
                        }
                            
                        $query = 'UPDATE room_student SET is_next = TRUE, waiting = FALSE WHERE team = 1';
                        $pdo->exec($query);
                    }  
                }
                    
                header("Location: ../room/room.php?room=".$room_id);
                exit();
                    
            } catch(PDOException $e){
                $form_errors['db'] = 'Неуспешен запис.';
                    
                header("Location: create_room_form.php?form_errors=".urlencode(serialize($errors)));
                exit();
            }
            ?>
        </p>
    </body>
</html>

<html>
    <body>
        <p>
            <?php
            
            $config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
            $base_dir = $config['BASE_FOLDER'];
            $base_url = $config['BASE_URL'];
            $room_path = $base_url.'/views/room/room.php';
            
            require_once "$base_dir/db/room.php";
            require_once "$base_dir/db/queue.php";
            
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }
            
            if(!isset($_SESSION['user_id']) | !isset($_SESSION['user_role']) | $_SESSION['user_role'] != 2){
                header("Location: $base_url/views/lobby.php");
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
            
            $roomModel = new RoomModel();
            $queueModel = new QueueModel();
            
            $target_file = null;
            $target_dir = "$base_dir/uploads/";
                
            if(!isset($_POST["submit"])){
                exit();
            }
            
            $errors = array();
                
            if(!isset($_POST['title']) | $_POST['title'] == ''){
                $errors['title'] = 'Заглавието е задължително поле.';
                
                header("Location: $base_dir/views/create_room.php?form_errors=".urlencode(serialize($errors)));
                exit();
            }
                      
            if(!empty($_FILES) & $_FILES["students"]["size"] > 0){
                $target_file = uploadFile($target_dir, $errors);
                
                if($target_file == null){
                    header("Location: $base_url/views/create_room.php?form_errors=".urlencode(serialize($errors)));
                    exit();
                }
            }   
               
            $title = $_POST["title"];
            $descr = $_POST["descr"];
            $url = $_POST["url"];
            $passwd = $_POST["password"];
            $type = $_POST["type"];
            $userid = $_SESSION["user_id"];
                
            $room_id = $roomModel->insertRoomDetails($title, $descr, $url, $passwd, $type, $userid);
                
            if($target_file != null){
                $file = fopen($target_file, 'r');
                
                if($file){
                    $queueModel->insertInQueue($file, $room_id);
                }  
            }
                    
            header("Location: $room_path?room=".$room_id);
            exit();
            ?>
        </p>
    </body>
</html>

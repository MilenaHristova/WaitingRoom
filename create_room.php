<html>
    <body>
        <p>
            <?php
                $title = null;
                $descr = null;
                $url = null;
                $passwd = null;
                $userid = 1;

                if(isset($_POST["title"])){
                    $title = $_POST["title"];
                }
                
                if(isset($_POST["descr"])){
                    $descr = $_POST["descr"];
                }

                if(isset($_POST["url"])){
                    $url = $_POST["url"];
                }
                
                if(isset($_POST["password"])){
                    $passwd = $_POST["password"];
                }
            
                if(isset($_SESSION["user_id"])){
                    $userid = $_SESSION["user_id"];
                }
            
                try{
                    $dbh = new PDO("mysql:host=localhost;dbname=waiting_room", 'root', 'admin');
                    $query = 'INSERT INTO rooms(creator_id, moderator_id, name, description, meeting_password, url) values('.$dbh->quote($userid).','.$dbh->quote($userid).','.$dbh->quote($title).','.$dbh->quote($descr).','.$dbh->quote($passwd).','.$dbh->quote($url).')';
                    
                    $dbh->exec($query);
                    $room_id = null;
                    foreach($dbh->query("SELECT LAST_INSERT_ID()") as $row){
                        $room_id = $row[0];
                        break;
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
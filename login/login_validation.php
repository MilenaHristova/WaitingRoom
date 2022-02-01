<?php
 if (session_status() === PHP_SESSION_NONE)
   {
           session_start();
   }

if ($_SERVER["REQUEST_METHOD"] == "POST") {


   require_once "../connect_db.php";

   $username = $_POST["username"];
   $password = $_POST["password"];

   $db = Database::getInstance();
   $pdo = $db->getConnection();

   $query = "SELECT * FROM users WHERE username LIKE \"$username\"";
   $statements = $pdo->query($query);
   if($statements->rowCount() == 0){
      $login_errors = "Потребителското име не съществува";
      if(isset($_REQUEST['room_id'])){
        header("Location: login.php?login_errors=$login_errors&room_id={$_REQUEST['room_id']}");
      }
      else{
        header("Location: login.php?login_errors=$login_errors");
      }

      exit();

   }

   $rows = $statements->fetchAll(PDO::FETCH_ASSOC);
   $passwd_hash = $rows[0]["password"];
   if(!password_verify($password, $passwd_hash)){
        $login_errors = "Грешна парола";
        if(isset($_REQUEST['room_id'])){
                header("Location: login.php?login_errors=$login_errors&room_id={$_REQUEST['room_id']}");
        }
        else{
            header("Location: login.php?login_errors=$login_errors");
        }
        exit();
   }

   $_SESSION["user_id"] = $rows[0]["user_id"];
   $_SESSION["name"] = $rows[0]["name"];
   $_SESSION["fn"] = $rows[0]["faculty_number"];
   $_SESSION['user_role'] = $rows[0]["role"];
   if(isset($_REQUEST['room_id'])){
      header("Location: ../room/queue.php?room={$_REQUEST['room_id']}");
   }
   else{
     header("Location: ../lobby/lobby.php");
   }


}
?>

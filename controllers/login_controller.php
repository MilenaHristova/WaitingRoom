<?php
 if (session_status() === PHP_SESSION_NONE)
   {
           session_start();
   }

$config = require_once $_SERVER['DOCUMENT_ROOT'].'/WaitingRoom/config.php';
$base_dir = $config['BASE_FOLDER'];
$base_url = $config['BASE_URL'];

require_once "$base_dir/db/users.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
   $users_model = new UsersModel();

   $username = $_POST["username"];
   $password = $_POST["password"];

   $rows = $users_model->getByUsername($username);
   
   if($rows == FALSE){
      $login_errors = "Потребителското име не съществува";
      if(isset($_REQUEST['room_id'])){
        header("Location: $base_url/views/login.php?login_errors=$login_errors&room_id={$_REQUEST['room_id']}");
      }
      else{
        header("Location: $base_url/views/login.php?login_errors=$login_errors");
      }

      exit();

   }

   $passwd_hash = $rows[0]["password"];
   if(!password_verify($password, $passwd_hash)){
        $login_errors = "Грешна парола";
        if(isset($_REQUEST['room_id'])){
                header("Location: $base_url/views/login.php?login_errors=$login_errors&room_id={$_REQUEST['room_id']}");
        }
        else{
            header("Location: $base_url/views/login.php?login_errors=$login_errors");
        }
        exit();
   }

   $_SESSION["user_id"] = $rows[0]["user_id"];
   $_SESSION["name"] = $rows[0]["name"];
   $_SESSION["fn"] = $rows[0]["faculty_number"];
   $_SESSION["user_role"] = $rows[0]["role"];
    
   if(isset($_REQUEST['room_id'])){
      header("Location: $base_url/views/room/room.php?room={$_REQUEST['room_id']}");
   }
   else{
     header("Location: $base_url/views/lobby.php");
   }


}
?>

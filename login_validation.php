<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    require_once 'connect_db.php';

   $username = $_POST["username"];
   $password = $_POST["password"];

   $db = Database::getInstance();
   $pdo = $db->getConnection();

   $query = "SELECT * FROM users WHERE username LIKE \"$username\"";
   $statements = $pdo->query($query);
   if($statements->rowCount() == 0){
      $_SESSION["login_errors"] = "Потребителското име не съществува";
      require_once("login.php");
      session_unset();
      exit();
   }

   $rows = $statements->fetchAll(PDO::FETCH_ASSOC);
   $passwd_hash = $rows[0]["password"];
   if(!password_verify($password, $passwd_hash)){
        $_SESSION["login_errors"] = "Грешна парола";
        require_once("login.php");
        session_unset();
        exit();
   }

   $_SESSION["user_id"] = $rows[0]["user_id"];
   require_once("lobby.php");

}
?>
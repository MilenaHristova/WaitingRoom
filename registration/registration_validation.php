<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    require_once '../connect_db.php';


    function test_username($username){
        if (empty($username)){
            return "Потребителското име е задължително поле";
        }

    	$length = mb_strlen($username);
    	if ($length < 3){
            return "Потребителското име трябва да е минимум 3 символа, а вие сте въвели $length";
        } else if ($length > 20){
            return "Потребителското име трябва да е максимум 20 символа, а вие сте въвели $length";
    	}

        $db = Database::getInstance();
        $pdo = $db->getConnection();

    	$query = "SELECT * FROM users WHERE users.username LIKE \"$username\"";
    	$statements = $pdo->query($query);
        if($statements->rowCount() > 0){
           return "Потребителското име вече е заето";
        }
   }

   function test_password($password){
        if(empty($password)){
            return "Паролата е задължително поле";
        }
        $length = mb_strlen($password);
        if($length < 8){
            return "Паролата трябва да е поне 8 символа, а вие сте въвели $length";
        }

   }

   function test_names($names){
        if(empty($names)){
            return "Имената са задължително поле";
        }
        $names_split = mb_split("\s", $names);
        if(count($names_split) != 2){
            return "Изискват се 2 имена";
        }
   }

   function test_fn($fn){
        if(empty($fn)){
            return "ФН е задължително поле";
        }

        if(!ctype_digit($fn)){
           return "ФН съдържа само цифри";
        }

        $length = mb_strlen($fn);
        if($length != 5){
            return "Грешен ФН";
        }
   }

   $username = $_POST["username"];
   $password = $_POST["password"];
   $names = $_POST["name"];
   $fn =  $_POST["fn"];

   $username_error = test_username($username);
   $password_error = test_password($password);
   $names_error = test_names($names);
   $fn_error = test_fn($fn);

   $errors = array();
   if($username_error != null){
        $errors['username'] = $username_error;
   }
   if($password_error != null){
        $errors['password'] = $password_error;
   }
   if($names_error != null){
       $errors['names'] = $names_error;
   }
   if($fn_error != null){
       $errors['fn'] = $fn_error;
   }

   if(empty($errors)){

        $passwd_hash = password_hash($password, PASSWORD_DEFAULT);

        $db = Database::getInstance();
        $pdo = $db->getConnection();

        $insert_query = "INSERT INTO users (faculty_number,name, role, username, password) VALUES (?, ?, ?, ?, ?)";
        $stmt= $pdo->prepare($insert_query);
        $stmt->execute([$fn, $names, 1, $username, $passwd_hash]);

        $query = "SELECT * FROM users WHERE users.username LIKE \"$username\"";
        $statements = $pdo->query($query);
        $rows = $statements->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION["user_id"] = $rows[0]['user_id'];
        $_SESSION["fn"] = $rows[0]['faculty_number'];
        $_SESSION["user_role"] = $rows[0]["role"];

        if(isset($_REQUEST['room_id'])){
              header("Location: ../room/queue.php?room={$_REQUEST['room_id']}");
        }
        else{
             header("Location: ../lobby/lobby.php?user_role={$rows[0]["role"]}&user_names={$rows[0]["name"]}");
        }
   		exit();
   }
   else{
   		header("Location: registration.php?registration_errors=".urlencode(serialize($errors) . "&room_id=" . $_REQUEST['room_id']));
   }

}
?>

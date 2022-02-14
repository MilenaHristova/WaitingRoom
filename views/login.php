<?php

 if (session_status() === PHP_SESSION_NONE)
   {
           session_start();
   }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="styles/login.css">
    <title>Влизане</title>
</head>
<body>
<article>
<div class="float_left">
<img src="../images/login_img.jpg" alt="Login">
</div>
<div class="float_right">
<?php if(!isset($_GET['room_id'])): ?>
    <h1>Добре дошли!</h1>
<?php else: ?>
    <h1>Моля влезте в акаунта си</h1>
<?php endif; ?>

<?php
if(isset($_REQUEST['room_id'])){
echo "<form method=\"post\" action=\"../controllers/login_controller.php?room_id={$_REQUEST['room_id']}\">";
}
else{
echo "<form method=\"post\" action=\"../controllers/login_controller.php\">";
}
?>



    <input class="field" type="text" id="username" name="username" placeholder="Потребителско име">


    <input class="field" type="password" id="password" name="password" placeholder="Парола">

    <input class="submit" type="submit" value="Влизане">


</form>
<?php
        if(isset($_REQUEST["login_errors"])){
            echo "<div class= \"errors\">";
            echo "<p>{$_REQUEST["login_errors"]}</p>";
            echo "</div>";
        }
?>

<?php
if(isset($_REQUEST['room_id'])){
echo "<div class=\"registration_link\"><a href=\"registration.php?room_id={$_REQUEST['room_id']}\"> Регистрирай се </a> <i class=\"arrow\"></i></div>";
}
else{
echo "<div class=\"registration_link\"><a href=\"registration.php\">Регистрирай се </a><i class=\"arrow\"></i></div>";
}
?>
</div>
</article>
</body>
</html>

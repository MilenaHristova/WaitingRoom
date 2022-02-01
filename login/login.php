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
    <link rel="stylesheet" href="login.css">
    <title>Влизане</title>
</head>
<body>
<article>
<?php if(!isset($_GET['room_id'])): ?>
    <h1>Добре дошли!</h1>
<?php else: ?>
    <h1>Моля влезте в акаунта си</h1>
<?php endif; ?>

<?php
if(isset($_REQUEST['room_id'])){
echo "<form method=\"post\" action=\"login_validation.php?room_id={$_REQUEST['room_id']}\">";
}
else{
echo "<form method=\"post\" action=\"login_validation.php\">";
}
?>


    <div class="field">
    <label for="username">Потребителско име</label>
    <input type="text" id="username" name="username">
    </div>
    <div class="field">
    <label for="password">Парола </label>
    <input type="password" id="password" name="password">
    </div>
    <input class="submit" type="submit" value="Влизане">
<?php
if(isset($_REQUEST['room_id'])){
echo "<div class=\"registration_link\"><a href=\"../registration/registration.php?room_id={$_REQUEST['room_id']}\"> Регистрирай се</a></div>";
}
else{
echo "<div class=\"registration_link\"><a href=\"../registration/registration.php\">Регистрирай се</a></div>";
}
?>

</form>
<?php
        if(isset($_REQUEST["login_errors"])){
            echo "<div class= \"errors\">";
            echo "<p>{$_REQUEST["login_errors"]}</p>";
            echo "</div>";
        }
?>
</article>
</body>
</html>

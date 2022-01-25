<?php
if(!isset($_SESSION["login_errors"])){
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
    <h1>Добре дошли!</h1>

<form method="post" action="login_validation.php">

    <div class="field">
    <label for="username">Потребителско име</label>
    <input type="text" id="username" name="username">
    </div>
    <div class="field">
    <label for="password">Парола </label>
    <input type="password" id="password" name="password">
    </div>
    <input class="submit" type="submit" value="Влизане">
    <div class="registration_link"><a href="registration.php">Регистрирай се</a></div>

</form>
<?php
        if(isset($_SESSION["login_errors"])){
            echo "<div class= \"errors\">";
            echo "<p>{$_SESSION["login_errors"]}</p>";
            echo "</div>";
        }
?>
</article>
</body>
</html>
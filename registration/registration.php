<?php
if(session_status() === PHP_SESSION_NONE){
session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="registration.css">
    <title>Регистрация</title>
</head>
<body>
<article>
    <h1>Регистрация</h1>
    <?php
    if(isset($_REQUEST['room_id'])){
    //$room_id = $_REQUEST['room_id'];
    echo "<form method=\"post\" action=\"registration_validation.php?room_id={$_REQUEST['room_id']}\">";
    }
    else{
    echo "<form id=\"form\" action=\"registration_validation.php\" method=\"post\" >";
    }
    ?>
        <div class="field">
            <label for="username">Потребителско име</label>
            <input type="text" name="username" >
        </div>
        <div class="field">
            <label for="password">Парола </label>
            <input type="password" name="password">
        </div>
        <div class="field">
            <label for="name">Имена</label>
            <input type="text" name="name">
        </div>
        <div class="field">
            <label for="fn">ФН</label>
            <input type="text" name="fn">
        </div>

        <input class="submit" type="submit" value="Регистрирай ме">

    </form>

    <?php
        if(isset($_REQUEST["registration_errors"])){
            $errors = unserialize(urldecode($_REQUEST["registration_errors"]));
            echo "<div class= \"errors\">";
            foreach($errors as $error){
                echo "<p>$error</p>";
            }
            echo "</div>";
        }
    ?>

</article>
</body>
</html>

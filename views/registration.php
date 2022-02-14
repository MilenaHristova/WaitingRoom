<?php
if(session_status() === PHP_SESSION_NONE){
session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="styles/registration.css">
    <title>Регистрация</title>
</head>
<body>
<article>
<div class="float_left">
<img src="../images/login_img.jpg" alt="Login">
</div>
<div class="float_right">
    <h1>Регистрация</h1>
    <?php
    if(isset($_REQUEST['room_id'])){
    //$room_id = $_REQUEST['room_id'];
    echo "<form method=\"post\" action=\"../controllers/registration_controller.php?room_id={$_REQUEST['room_id']}\">";
    }
    else{
    echo "<form id=\"form\" action=\"../controllers/registration_controller.php\" method=\"post\" >";
    }
    ?>
            <input class="field" type="text" name="username" placeholder="Потребителско име">
            <input class="field" type="password" name="password" placeholder="Парола (поне 8 символа)">
            <input class="field" type="text" name="name" placeholder="Име и фамилия">
            <input class="field" type="text" name="fn" placeholder="ФН">
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
	
	<?php
if(isset($_REQUEST['room_id'])){
echo "<div class=\"login_link\"><a href=\"login.php?room_id={$_REQUEST['room_id']}\"> Вече имате акаунт? Влезте в акаунта си </a> <i class=\"arrow\"></i></div>";
}
else{
echo "<div class=\"login_link\"><a href=\"login.php\">Вече имате акаунт? Влезте в акаунта си </a><i class=\"arrow\"></i></div>";
}
?>
</div>
</article>
</body>
</html>

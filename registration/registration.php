<?php
if(!isset($_SESSION["registration_errors"])){
session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="registration.css">
    <title>Регистрация</title>
    <script type="text/javascript">
        function show_hide_fn() {
            var student_option = document.getElementById("student");
            var fn_div = document.getElementById("fn_field");
            fn_div.style.display = student_option.checked ? "block" : "none";
        }
    </script>
</head>
<body>
<article>
    <h1>Регистрация</h1>
    <form id="form" action="registration_validation.php" method="post" >
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
            <label for="role">Роля</label>
            <div class="role_option">
                <input type="radio" id="student" value="1" name="role_options" checked="checked" onclick="show_hide_fn()">
                <label for="student">Студент</label>
             </div>
             <div class="role_option">
                <input type="radio" id="professor" value ="2" name="role_options" onclick="show_hide_fn()">
                <label for="professor">Професор</label>
             </div>
        </div>
        <div class="field" id="fn_field">
            <label for="fn">ФН</label>
            <input type="text" name="fn">
        </div>

        <input class="submit" type="submit" value="Регистрирай ме">

    </form>

    <?php
        if(isset($_SESSION["registration_errors"])){
            echo "<div class= \"errors\">";
            foreach($_SESSION["registration_errors"] as $error){
                echo "<p>$error</p>";
            }
            echo "</div>";
        }
    ?>

</article>
</body>
</html>

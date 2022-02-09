<html>
<head>
<meta charset="UTF-8"/>
<title> Чакалня </title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="create_room.css">
</head>

<body>
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_id']) | !isset($_SESSION['user_role']) | $_SESSION['user_role'] != 2){
    header("Location: ../lobby/lobby.php");
    exit();
}

?>
    <div class="navbar">
        <button><a href="../lobby/lobby.php">Изход</a></button>
    </div>
    <article>
    <div class="container">
        <div class="create-form">
            <h2 class="title">Създаване на стая</h2>
            <form action="create_room.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="title">Име: </label>
                    <input type="text" name="title" id="title" class="input">
                </div>
                <div>
                    <label for="descr">Описание: </label>
                    <input type="text" name="descr" id="descr" class="input">
                </div>
                <div>
                    <div>
                        <label for="descr">Вид: </label>
                        <input type="radio" name="type" id="project_defense" value="project_defense">
                        <label for="project_defense">Защита на проект</label>
                        <input type="radio" name="type" id="referat" value="referat">
                        <label for="referat">Представяне на реферат</label>
                        <input type="radio" name="type" id="mark" value="mark">
                        <label for="mark">Нанасяне на оценка</label>
                    </div>
                    
                </div>
                <div>
                    <label for="url">Линк към срещата (за онлайн стаи): </label>
                    <input type="text" name="url" id="url" class="input">
                </div>
                <div>
                    <label for="password">Парола за срещата (за онлайн стаи): </label>
                    <input type="text" name="password" id="password" class="input">
                </div>
                <div>
                    <label for="file">Списък със студенти: </label>
                    <input type="file" name="students" id="students" class="button-upload">
                </div>
                <div class="submit">
                    <input type="submit" name="submit" value="Напред" class="button-ok">
                </div> 
            </form>
        </div>
    </div>
    
    <div class="errors">
    <?php
        if(isset($_REQUEST["form_errors"])){
            $errors = unserialize(urldecode($_REQUEST["form_errors"]));
            foreach($errors as $error){ 
                echo '<p>'.$error.'</p>';
            }
        }
    ?>
    </div>
        
</article>
    
    

</body>

</html>

<html>
<head>
<meta charset="UTF-8"/>
<title> Чакалня </title>
    <link rel="stylesheet" href="queue.css">
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
    <div class="container">
        <div class="navbar">
            <button><a href="../lobby/lobby.php">Изход</a></button>
        </div>
        
        <div class="create-form">
            <h2>Създаване на стая</h2>
            <form action="create_room.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="title">Име: </label>
                    <input type="text" name="title" id="title">
                </div>
                <div>
                    <label for="descr">Описание: </label>
                    <input type="text" name="descr" id="descr">
                </div>
                <div>
                    <label for="descr">Вид: </label>
                    <input type="radio" name="type" id="project_defense" value="project_defense">
                    <label for="project_defense">Защита на проект</label>
                    <input type="radio" name="type" id="referat" value="referat">
                    <label for="referat">Представяне на реферат</label>
                    <input type="radio" name="type" id="mark" value="mark">
                    <label for="mark">Нанасяне на оценка</label>
                </div>
                <div>
                    <label for="url">Линк към срещата (за онлайн стаи): </label>
                    <input type="text" name="url" id="url">
                </div>
                <div>
                    <label for="password">Парола за срещата (за онлайн стаи): </label>
                    <input type="text" name="password" id="password">
                </div>
                <div>
                    <label for="file">Списък със студенти: </label>
                    <input type="file" name="students" id="students">
                </div>
                <input type="submit" name="submit" value="Напред">
            </form>
        </div>
    </div>
    
    

</body>

</html>
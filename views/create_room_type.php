<html>
<head>
<meta charset="UTF-8"/>
<title> Чакалня </title>
    <link rel="stylesheet" href="styles/create.css">
    <link rel="stylesheet" href="styles/common.css">
</head>

<body>
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_id']) | !isset($_SESSION['user_role']) | $_SESSION['user_role'] != 2){
    header("Location: lobby.php");
    exit();
}

?> 
    <header>
        <form method="get" action="create_room.php">
            <button  type="submit" class="header_button">Назад</button>
        </form>
    </header>
    <div class="container">
        <div class="create-form">
            <h1 class="title">Създаване на тип стая</h1>
            <form action="../controllers/create_room_type_controller.php" method="post" enctype="multipart/form-data">
                <div>
                    <input type="text" name="name" id="name" class="field" placeholder="Име">
                </div>
                <div>
                    <input type="text" name="avg_time" id="avg_time" class="field" placeholder="Средно очаквано време на човек (минути)">
                </div>
                <div>
                    <input class="submit" type="submit" name="submit" value="Създай">
                </div> 
            </form>
        </div>
        
        <div class="errors">
    <?php
        if(isset($_REQUEST["errors"])){
                echo '<p>'.$_REQUEST["errors"].'</p>';
        }
    ?>
    </div>
        
    </div>
    
    
    
    

</body>

</html>

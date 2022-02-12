<html>
<head>
<meta charset="UTF-8"/>
<title> Чакалня </title>
    <link rel="stylesheet" href="create.css">
    <link rel="stylesheet" href="../common.css">
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
    <header>
        <form method="get" action="../lobby/lobby.php">
            <button  type="submit" class="header_button">Излез</button>
        </form>
    </header>
    <div class="container">
        <div class="create-form">
            <h1 class="title">Създаване на стая</h1>
            <form action="create_room.php" method="post" enctype="multipart/form-data">
                <div>
                    <input type="text" name="title" id="title" class="field" placeholder="Име">
                </div>
                <div>
                    <input type="text" name="descr" id="descr" class="field" placeholder="Описание">
                </div>
                <div class="room_types">
                    
					<?php
						require '../connect_db.php';
						$db = Database::getInstance();
						$pdo = $db->getConnection();
						$query = 'SELECT * FROM room_type';
						$st = $pdo->query($query);
						while(($row = $st->fetch(PDO::FETCH_ASSOC)) != FALSE){
							echo "<div class=\"row\">
							<input type=\"radio\" name=\"type\" id={$row['type']} value={$row['type']}>
							<label for={$row['type']}>{$row['name']} (~ {$row['avg_time']} минути)</label>
							</div>";
						}
						
					?>
					
                </div>
                <div>
                    <input type="text" name="url" id="url" class="field" placeholder="Линк към срещата (за онлайн стаи)">
                </div>
                <div>
                    <input type="text" name="password" id="password" class="field" placeholder="Парола за срещата (за онлайн стаи)">
                </div>
                <div class="row">
                    <label for="file">Списък със студенти: </label>
                    <input type="file" name="students" id="students" class="button-upload">
                </div>
                <div>
                    <input class="submit" type="submit" name="submit" value="Напред">
                </div> 
            </form>
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
        
    </div>
    
    
    
    

</body>

</html>

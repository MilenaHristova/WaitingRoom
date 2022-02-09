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
    <link rel="stylesheet" href="../lobby/lobby.css">
    <title>Lobby</title>
</head>
<body>
<header>
    <?php if(!isset($_SESSION["name"])) : ?>
    <form method="get" action="../registration/registration.php">
            <button  type="submit" class="registration_button">Регистрирай се</button>
    </form>
    <form method="get" action="../login/login.php">
        <button type="submit" class="login_button">Влез в акаунт</button>
    </form>
    <?php else: ?>
     <?php
        $temp_role = $_SESSION['user_role'];
        echo "<p>Добре дошли, {$_SESSION["name"]}</p>";
     ?>
     <form method="get" action="exit_lobby.php">
                 <button  type="submit" class="exit_button">Излез</button>
     </form>
    <?php endif; ?>
</header>

<div class="rooms_list">
<?php
require_once '../connect_db.php';
$db = Database::getInstance();
$pdo = $db->getConnection();
$query = 'SELECT name, description, room_id FROM rooms';
$statements = $pdo->query($query);
$rows = $statements->fetchAll(PDO::FETCH_ASSOC);
if($rows){
    foreach($rows as $row){
        if($_SESSION["user_role"] == 2){
            $str = "<button class=\"join_button\" type=\"button\"><a href=\"../room/room_admin.php?room={$row['room_id']}\">Влез</a></button>";
        } elseif($_SESSION["user_role"] == 1){
            $str = "<button class=\"join_button\" type=\"button\"><a href=\"../room/room.php?room={$row['room_id']}\">Влез</a></button>";
        }
        
        echo "<div class=\"room\">
        <p>Име на стая: {$row['name']}</p>
        <p>Описание: {$row['description']}</p>
        {$str}
        </div>";
    }
}
?>
</div>

<div class="create_room">
<?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2) : ?>
        <button id="create_room_btn" type="button"><a href="../create_room/create_room_form.php">Създай стая</a></button>
<?php endif; ?>
</div>
</body>
</html>

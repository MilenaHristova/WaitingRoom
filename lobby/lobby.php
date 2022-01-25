<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="../lobby/lobby.css">
    <title>Lobby</title>
</head>
<body>
<header>
<?php
if(session_status() === PHP_SESSION_NONE){
session_start();
}
if(isset($_SESSION['user_id'])){
    require_once '../connect_db.php';
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE users.user_id LIKE $user_id";
    $statements = $pdo->query($query);
    $rows = $statements->fetchAll(PDO::FETCH_ASSOC);
    $user_details = $rows[0];
    echo "<p>Добре дошли, {$user_details['name']}</p>";
}
?>


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
        echo "<div class=\"room\">
        <p>Име на стая: {$row['name']}</p>
        <p>Описание: {$row['description']}</p>
        <form method=\"post\" action=\"../room/queue.php\">
            <input type=\"hidden\" name=\"room_id\" value=\"{$row['room_id']}\">
            <button class=\"join_button\" type=\"submit\">Влез</button>
        </form>
        </div>";
    }
}
?>
    
</div>

<div class="create_room">
<?php
if(isset($_SESSION['user_id'])){
    if($user_details['role'] == "2"){
        echo "<button id=\"create_room_btn\" type=\"button\">Създай стая</button>";
    }
}
?>
</div>
</body>
</html>

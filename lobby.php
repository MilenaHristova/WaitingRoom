<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="lobby.css">
    <title>Lobby</title>
</head>
<body>
<header>
    <p>menu</p>

</header>

<div class="rooms_list">
<?php
require_once 'connect_db.php';
$db = Database::getInstance();
$pdo = $db->getConnection();
$query = 'SELECT name, description FROM rooms';
$statements = $pdo->query($query);
$rows = $statements->fetchAll(PDO::FETCH_ASSOC);
if($rows){
    foreach($rows as $row){
        echo "<div class=\"room\">
        <p>Име на стая: {$row['name']}</p>
        <p>Описание: {$row['description']}</p>
        <button class=\"join_button\" type=\"button\">Влез</button>
        </div>";
    }
}
?>
    
</div>

<div class="create_room">
<?php if(5 == 5) : ?>
        <button id="create_room_btn" type="button">Създай стая</button>
<?php endif; ?>
</div>
</body>
</html>
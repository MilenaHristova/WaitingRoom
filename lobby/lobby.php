<?php
 if (session_status() === PHP_SESSION_NONE)
   {
           session_start();
   }
  
   
 include_once 'lobby_operations.php';
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
            <button  type="submit" class="header_button">Регистрирай се</button>
    </form>
    <form method="get" action="../login/login.php">
        <button type="submit" class="header_button">Влез в акаунт</button>
    </form>
    <?php else: ?>
     <?php
        $temp_role = $_SESSION['user_role'];
        echo "<p>Добре дошли, {$_SESSION["name"]}</p>";
     ?>
     <form method="post" action="lobby_operations.php">
                 <input  type="submit" class="header_button" name="exit" value="Излез">
     </form>
	 <div class="create_room">
		<?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2) : ?>
			<button class="header_button" type="button"><a href="../create_room/create_room_form.php">Създай стая</a></button>
		<?php endif; ?>
	 </div>

    <?php endif; ?>
		 <form method = "post" action="lobby_operations.php">
				<input type="text" class="search_field" name="key_word" placeholder="Търсене на стая">
				<input type="hidden" class="header_button" name="search_rooms" value="Търси">
	 </form>
	
</header>

<div class="rooms_list">
<?php
/*require_once '../connect_db.php';
$db = Database::getInstance();
$pdo = $db->getConnection();
$query = 'SELECT name, description, room_id FROM rooms';
$statements = $pdo->query($query);
$rows = $statements->fetchAll(PDO::FETCH_ASSOC);*/
if(isset($_SESSION['rooms'])){
	$rows = $_SESSION['rooms'];
	unset($_SESSION['rooms']);
}
else{
	$rows = getAllRooms();
}


if($rows){
    foreach($rows as $row){
        echo "<div class=\"room\">
        <div class=\"room_name\"><p>{$row['name']}</p></div>
        <div class=\"room_description\"><p>{$row['description']}</p></div>
        <button class=\"join_button\" type=\"button\"><a href=\"../room/room.php?room={$row['room_id']}\">Влез</a></button>
        </div>";
    }
}
?>
</div>


</body>
</html>

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
    <div class="room" id="room_1">
        <p>Име на стая: name</p>
        <p>Описание: description</p>
        <button type="button">Влез</button>
    </div>

    <div class="room" id="room_2">
        <p>Name: name2</p>
        <p>description: description2</p>
        <button type="button">Join</button>
    </div>


</div>

<div class="create_room">
<?php if(5 == 5) : ?>
        <button id="create_room_btn" type="button">Създай стая</button>
<?php endif; ?>
</div>
</body>
</html>
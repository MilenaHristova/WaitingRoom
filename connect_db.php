<?php

$host = 'localhost';
$db = 'waiting_room';
$user = 'root';
$password = '';

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

try {
	$pdo = new PDO($dsn, $user, $password);

} catch (PDOException $e) {
	echo 'failed';
    echo $e->getMessage();
}
?>
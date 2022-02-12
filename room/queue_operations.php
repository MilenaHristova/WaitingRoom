<?php

session_start();
require_once('../connect_db.php');

date_default_timezone_set('Europe/Sofia');

function loadQueue($room_id, $count){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT user_id, u.name, faculty_number, rs.time FROM users u
         JOIN room_student rs ON u.user_id = rs.student_id
         WHERE rs.room_id = '.$room_id.' AND rs.waiting = TRUE LIMIT '.$count.';';
    
    $res = array();
    
    $st = $pdo->query($query);
    while(($row = $st->fetch(PDO::FETCH_ASSOC)) != FALSE){
        if($row["time"] == null | $row["time"] == ''){
            $time = null;
        } else {
            $time = date('H:i', strtotime($row["time"]));
        }
        
        array_push($res, array("id" => $row["user_id"], "name" => $row["name"], "fn" => $row["faculty_number"], "time" => $time));
    }
    
    return $res;
}

function loadRoomDescr($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    $query = 'SELECT * FROM rooms WHERE room_id = '.$room_id;
    $st = $pdo->query($query);
    $result = $st->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function updateNext($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT team FROM room_student WHERE room_id = '.$room_id.' AND is_next = TRUE;';
    $st = $pdo->query($query);
    $result = $st->fetch(PDO::FETCH_ASSOC);
    
    if($result == FALSE){
        $curr = 0;
    } else {
		$date = date('Y-m-d H:i:s', time());
        $curr = $result["team"];
        $sql = 'UPDATE room_student SET is_next = FALSE, in_room = TRUE, waiting = FALSE, in_time = ? 
		WHERE room_id = ? AND team = ?;';
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$date, $room_id, $curr]);
    }
    
	
	
    $sql = 'UPDATE room_student 
    SET is_next = TRUE 
	WHERE room_id = ? AND team = ? AND waiting = TRUE';
	$stmt= $pdo->prepare($sql);
	$curr = $curr + 1; 
	$stmt->execute([$room_id, $curr]);
    
    header("Location: room.php?room=".$room_id);
    exit();
}

function getNext($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $sql = 'SELECT faculty_number FROM users u 
              JOIN room_student rs ON u.user_id = rs.student_id
              WHERE rs.room_id = '.$room_id.' AND rs.is_next = 1';
    
    $st = $pdo->query($sql);
    
    $res = array();
    while(($row = $st->fetch(PDO::FETCH_ASSOC)) != FALSE){
        array_push($res, $row["faculty_number"]);
    }
   
    if(empty($res)){
		if(!getInRoom($room_id)){
			updateAvgTimeOfType($room_id);
		}
        return FALSE;
    }
    
    return $res;
}

function getInRoom($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $sql = 'SELECT user_id, faculty_number, name FROM users u 
            JOIN room_student rs ON u.user_id = rs.student_id
            WHERE rs.room_id = '.$room_id.' AND rs.in_room = TRUE';
    
    $st = $pdo->query($sql);
    
    $res = array();
    while(($row = $st->fetch(PDO::FETCH_ASSOC)) != FALSE){
        array_push($res, array("fn" => $row["faculty_number"], "name" => $row["name"], "id" => $row["user_id"]));
    }
   
    if(empty($res)){
        return FALSE;
    }
    
    return $res;
}

function searchByFn($fn){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT name, user_id FROM users WHERE faculty_number = '.$fn;
    $st = $pdo->query($query);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row;
}

function getStudentTime($id, $room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $query = 'SELECT time FROM room_student WHERE student_id = '.$id.' AND room_id = '.$room_id;
    $st = $pdo->query($query);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    return $row == FALSE ? FALSE : $row['time'];
}

function setBreak($room_id, $mins){
    $now = time();
    $end = $now + (60 * $mins);
    $end_time = date('Y-m-d H:i', $end);
    
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    $sql = 'UPDATE rooms SET break_until = '.($pdo->quote($end_time)).' WHERE room_id = '.$room_id;
    $pdo->exec($sql);    
}

function getBreak($room_id){
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    $sql = 'SELECT break_until FROM rooms WHERE room_id = '.$room_id;
    $st = $pdo->query($sql);
    $res = $st->fetch(PDO::FETCH_ASSOC);
    
    return $res == FALSE ? FALSE : $res['break_until'];
}

function getAvgTime($room_id){
	$db = Database::getInstance();
    $pdo = $db->getConnection();
    $sql = 'SELECT avg_time FROM rooms WHERE room_id = '.$room_id;
	$st = $pdo->query($sql);
    $res = $st->fetch(PDO::FETCH_ASSOC);
	
	return $res == FALSE ? FALSE : $res['avg_time'];
}

function getEstimatedWaitingTime($room_id, $student_id){
	$db = Database::getInstance();
    $pdo = $db->getConnection();
	
	$avg_time = getAvgTime($room_id);
	
	$sql = 'SELECT team FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$student_id; 
	$st = $pdo->query($sql);
    $res = $st->fetch(PDO::FETCH_ASSOC);
	$student_team = $res['team'];
	
	$sql='SELECT * FROM room_student WHERE room_id = '.$room_id.' AND waiting = TRUE AND team < '.$student_team.' GROUP BY team';
	$st = $pdo->query($sql);
    $before = $st->rowCount();
	
	return $before * $avg_time;
}

function checkIfInQueue($room_id, $student_id){
	$db = Database::getInstance();
    $pdo = $db->getConnection();
	
    $sql = 'SELECT * FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$student_id;
	$st = $pdo->query($sql);
    $res = $st->fetch(PDO::FETCH_ASSOC);
	
	return $res == FALSE ? FALSE : TRUE;
}

function addInQueue($room_id, $student_id){
	$db = Database::getInstance();
    $pdo = $db->getConnection();
	
	$sql = 'SELECT MAX(team) as maxTeam FROM room_student WHERE room_id = '.$room_id;
	$st = $pdo->query($sql);
    $res = $st->fetch(PDO::FETCH_ASSOC);
	
	$max_team = $res['maxTeam'] != NULL ? $res['maxTeam'] : 1;  
	
	$sql = 'INSERT INTO room_student (room_id, student_id, team, waiting) VALUES (?,?,?,?)';
	$st= $pdo->prepare($sql); 
	$st->execute([$room_id, $student_id, $res['maxTeam'] + 1, TRUE]);
	
}

function removeFromQueue($room_id, $student_id){
	$db = Database::getInstance();
    $pdo = $db->getConnection();
	
	$sql = 'DELETE FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$student_id;
	$st = $pdo->query($sql);
}

function updateAvgTimeOfType($room_id){
	$db = Database::getInstance();
    $pdo = $db->getConnection();
    
	$sql = 'SELECT avg_time, type_id FROM rooms WHERE room_id = '.$room_id;
	$st = $pdo->query($sql);
    $res = $st->fetch(PDO::FETCH_ASSOC);
	$new_avg = $res['avg_time'];
	$type_id = $res['type_id'];
	if($type_id != NULL){
		$sql = 'SELECT avg_time FROM room_type WHERE id = ?';
		$st= $pdo->prepare($sql); 
		$st->execute([$type_id]);
		$res = $st->fetch(PDO::FETCH_ASSOC);
		$old_avg = $res['avg_time'] > 0 ? $res['avg_time'] : $new_avg;
	
		$sql = 'UPDATE room_type SET avg_time = ? WHERE id = ?';
		$st= $pdo->prepare($sql); 
		$st->execute([($old_avg + $new_avg) / 2, $type_id]);
	}
}

if(isset($_POST["next"])){
    updateNext($_POST["room_id"]);
} elseif(isset($_POST['remove_me'])){
	
	$room_id = $_POST["room_id"];
	$student_id = $_POST['user_id'];
	removeFromQueue($room_id, $student_id);
	header("Location: ../room/room.php?room=".$room_id);
	
} elseif(isset($_POST['add_me'])){
	
	$room_id = $_POST["room_id"];
	$student_id = $_POST['user_id'];
	addInQueue($room_id, $student_id);
	header("Location: ../room/room.php?room=".$room_id);
	
} elseif(isset($_POST["break"])){
    $room_id = $_POST["room_id"];
    if(!isset($_POST["mins"])){
        header("Location: ../room/room.php?room=".$room_id);
        exit(); 
    
    }
    setBreak($room_id, $_POST["mins"]);
    header("Location: ../room/room.php?room=".$room_id);
    exit();
}


















?>

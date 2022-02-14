<?php

require_once('connect_db.php');

class QueueModel{
    private $pdo;
    
    public function __construct(){
        $db = Database::getInstance();
        $this->pdo = $db->getConnection();
        date_default_timezone_set('Europe/Sofia');
    }
    
    function loadQueue($room_id, $count){
        $query = 'SELECT user_id, u.name, faculty_number, rs.time FROM users u
             JOIN room_student rs ON u.user_id = rs.student_id
             WHERE rs.room_id = '.$room_id.' AND rs.waiting = TRUE LIMIT '.$count.';';
        
        $res = array();
        
        $st = $this->pdo->query($query);
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
    
    function insertInQueue($file, $room_id){
        $teams = array();
        $team_num = 1;
        $insert_sql = 'INSERT INTO room_student(room_id, student_id, time, team) VALUES (?, ?, ?, ?)';
    
        while (($row = fgetcsv($file)) !== FALSE){
            $fn = $row[0];
            if($fn == ''){
                continue;
            }
    
            if($row[1] != ''){
                $t = date_parse_from_format("d/m/Y H:i", $row[1]);
                $tstamp = mktime($t["hour"], $t["minute"], 0, $t["month"], $t["day"], $t["year"]);
                $time = date("Y-m-d H:i", $tstamp);
            }
                                        
            $team_lead = $row[2];
            
            if($team_lead == $fn || $team_lead == ''){
                $teams[$fn] = $team_num;
                $team = $team_num;
                $team_num += 1;
            } else {
                $team = $teams[$team_lead];
            }
                                
            $id_query = 'SELECT user_id FROM users WHERE faculty_number = '.$fn;
            $st = $this->pdo->query($id_query);
            $res = $st->fetch(PDO::FETCH_ASSOC);
            $id = $res["user_id"];
            if($id == null){
                continue;
            }
                
            $stmt= $this->pdo->prepare($insert_sql);
            $stmt->execute([$room_id, $id, $time, $team]);
        }
            
        $query = 'UPDATE room_student SET is_next = TRUE WHERE team = 1';
        $this->pdo->exec($query);
    }
    
    function remove_all_from_room($room_id, $waiting){
        if(isset($_POST['remove_all'])){
            $sql = 'UPDATE room_student SET in_room = FALSE WHERE in_room = TRUE AND room_id = ?;';
        } else {
            $sql = "UPDATE room_student SET in_room = FALSE, waiting = $waiting WHERE in_room = TRUE AND room_id = ?;";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$room_id]); 
    }
    
    function isStudentInRoom($room_id, $student_id){
        $sql = 'SELECT * FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$student_id;
        
        $st = $this->pdo->query($sql);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row != FALSE;
    }
    
    function updateNext($room_id){
        $query = 'SELECT team FROM room_student WHERE room_id = '.$room_id.' AND is_next = TRUE;';
        $st = $this->pdo->query($query);
        $result = $st->fetch(PDO::FETCH_ASSOC);
        
        if($result == FALSE){
            $curr = 0;
        } else {
	   	   $date = date('Y-m-d H:i:s', time());
            $curr = $result["team"];
            $sql = 'UPDATE room_student SET is_next = FALSE, in_room = TRUE, waiting = FALSE, in_time = ? 
	   	       WHERE room_id = ? AND team = ?;';
            $stmt= $this->pdo->prepare($sql);
            $stmt->execute([$date, $room_id, $curr]);
        }
	   
        $sql = 'UPDATE room_student 
        SET is_next = TRUE 
	           WHERE room_id = ? AND team = ? AND waiting = TRUE';
	    $stmt= $this->pdo->prepare($sql);
	    $curr = $curr + 1; 
	    $stmt->execute([$room_id, $curr]);
    }
    
    function getNext($room_id){
        $sql = 'SELECT faculty_number FROM users u 
                  JOIN room_student rs ON u.user_id = rs.student_id
                  WHERE rs.room_id = '.$room_id.' AND rs.is_next = 1';
        
        $st = $this->pdo->query($sql);
        
        $res = array();
        while(($row = $st->fetch(PDO::FETCH_ASSOC)) != FALSE){
            array_push($res, $row["faculty_number"]);
        }
    
        if(empty($res)){
	   	   if(!$this->getInRoom($room_id)){
               $roomModel = new RoomModel();
	   		   $roomModel->updateAvgTimeOfType($room_id);
	   	   }
            return FALSE;
        }
        
        return $res;
    }
    
    function getInRoom($room_id){
        $sql = 'SELECT user_id, faculty_number, name FROM users u 
                JOIN room_student rs ON u.user_id = rs.student_id
                WHERE rs.room_id = '.$room_id.' AND rs.in_room = TRUE';
        
        $st = $this->pdo->query($sql);
        
        $res = array();
        while(($row = $st->fetch(PDO::FETCH_ASSOC)) != FALSE){
            array_push($res, array("fn" => $row["faculty_number"], "name" => $row["name"], "id" => $row["user_id"]));
        }
    
        if(empty($res)){
            return FALSE;
        }
        
        return $res;
    }
    
    function getStudentTime($id, $room_id){
        $query = 'SELECT time FROM room_student WHERE student_id = '.$id.' AND room_id = '.$room_id;
        $st = $this->pdo->query($query);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row == FALSE ? FALSE : $row['time'];
    }
    
    function getEstimatedWaitingTime($room_id, $student_id){
	   $r = new RoomModel();
        $avg_time = $r->getAvgTime($room_id);
	   
	   $sql = 'SELECT team FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$student_id; 
	   $st = $this->pdo->query($sql);
        $res = $st->fetch(PDO::FETCH_ASSOC);
	   $student_team = $res['team'];
	   
	   $sql='SELECT * FROM room_student WHERE room_id = '.$room_id.' AND waiting = TRUE AND team < '.$student_team.' GROUP BY team';
	   $st = $this->pdo->query($sql);
        $before = $st->rowCount();
	   
	   return $before * $avg_time;
    }
    
    function checkIfInQueue($room_id, $student_id){
        $sql = 'SELECT * FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$student_id;
	    $st = $this->pdo->query($sql);
        $res = $st->fetch(PDO::FETCH_ASSOC);
	   
	   return $res == FALSE ? FALSE : TRUE;
    }
    
    function addInQueue($room_id, $student_id){
	   $sql = 'SELECT MAX(team) as maxTeam FROM room_student WHERE room_id = '.$room_id;
	   $st = $this->pdo->query($sql);
       $res = $st->fetch(PDO::FETCH_ASSOC);
	   
	   $max_team = $res['maxTeam'] != NULL ? $res['maxTeam'] : 1;  
	   
	   $sql = 'INSERT INTO room_student (room_id, student_id, team, waiting) VALUES (?,?,?,?)';
	   $st= $this->pdo->prepare($sql); 
	   $st->execute([$room_id, $student_id, $res['maxTeam'] + 1, TRUE]);
    }
    
    function removeFromQueue($room_id, $student_id){
	   $sql = 'DELETE FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$student_id;
	   $st = $this->pdo->query($sql);
    }
    
    function invite($room_id, $id, $waiting){
        if($this->isStudentInRoom($room_id, $id)){
            $sql = 'UPDATE room_student 
            SET waiting = '.$waiting.', in_room = TRUE WHERE room_id = '.$room_id.' AND student_id = '.$id.';';

            $this->pdo->exec($sql);
        } else {
            $sql = 'SELECT MAX(team) FROM room_student WHERE room_id = '.$room_id.';';
            $st = $this->pdo->query($query);
            $row = $st->fetch(PDO::FETCH_ASSOC);
            if($row){
                $team = $row['team'] + 1;
            } else {
                $team = 1;
            }
            
            $sql = 'INSERT INTO room_student(room_id, student_id, team, in_room, waiting) VALUES (?, ?, ?, ?)';
            $stmt= $this->pdo->prepare($sql);
            $stmt->execute([$room_id, $id, $team, TRUE, $waiting]);
        }
    }
    
    function inviteAll($room_id, $waiting){
        $sql = 'UPDATE room_student 
        SET waiting = '.$waiting.', in_room = TRUE WHERE room_id = '.$room_id.' AND waiting = TRUE AND in_room = FALSE;';

        $this->pdo->exec($sql);
    }
    
    function setOutTime($date, $room_id, $id){
        $query = 'UPDATE room_student 
        SET in_room = FALSE, out_time= ? WHERE room_id = ? AND student_id = ?';
	    $stmt = $this->pdo->prepare($query);
	    $stmt->execute([$date, $room_id, $id]);
    }
    
    function getInTime($room_id, $id){
        $query_in_time = 'SELECT in_time FROM room_student WHERE room_id = '.$room_id.' AND student_id = '.$id;
	    $st = $this->pdo->query($query_in_time);
        return $st->fetch(PDO::FETCH_ASSOC);
    }
    
    function getAvgTime($room_id){
        $query = 'SELECT avg_time, passed_people FROM rooms WHERE room_id ='.$room_id;
	    $st = $this->pdo->query($query);
        return $st->fetch(PDO::FETCH_ASSOC);
    }
    
    function setAvgTime($avg_time, $room_id){
        $sql = 'UPDATE rooms SET passed_people = passed_people + 1, avg_time = '.$avg_time.' WHERE room_id ='.$room_id;
	    $this->pdo->exec($sql);
    }
}













?>
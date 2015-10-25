<?php

require_once __DIR__  . '/../../Database/Mysql.php';

class MovePlayerToSI{
	private $db = null;
	private $eventid = 0;
	private $uid = 0;
	private $date = "";
	
	public function goHome(){
		header('Location: ../../calendar/event/?date='.$this->date);
		exit();
	}
	
	public function validValues($val){
		if ($val != 0 and $val != null)
			return true;
		return false;
	}
	
	public function getRole(){
		$class = $this->db->query('SELECT * FROM user_char WHERE mainChar=1 AND uid ='.$this->uid)->fetch();
		switch($class->class){
			case "Warrior":
				if($class->spec == "Protection"){
					return 3;
				}else{
					return 1;
				}
			case "Rogue":
				return 1;
			case "Priest":
				if($class->spec == "Shadow"){
					return 1;
				}else{
					return 2;
				}
			case "Druid":
				if($class->spec == "Feral"){
					return 1;
				}else{
					return 2;
				}
			case "Hunter":
				return 1;
			case "Paladin":
				if($class->spec == "Retribition"){
					return 1;
				}elseif($class->spec == "Protection"){
					return 3;
				}else{
					return 2;
				}
			case "Mage":
				return 1;
			case "Warlock":
				return 1;
		}
	}
	
	public function commit(){
		if ($this->validValues($this->eventid) and $this->validValues($this->uid)){
			if($this->db->query('SELECT * FROM calender_event_participants WHERE eventid='.$this->eventid.' AND uid ='.$this->uid)->rowCount() > 0){
				$this->db->query('UPDATE calender_event_participants SET role = '.$this->getRole().' WHERE eventid='.$this->eventid.' AND uid ='.$this->uid);
			}else{
				$class = $this->db->query('SELECT * FROM user_char WHERE mainChar=1 AND uid ='.$this->uid)->fetch();
				$this->db->query('INSERT INTO calender_event_participants (eventid, uid, role, note, charid, date) VALUES ('.$this->eventid.', '.$this->uid.', '.$this->getRole().', "", '.$class->charid.', "'.$this->date.'")');
			}
			$this->goHome();
		}
	}
	
	public function __construct($db, $eventid, $uid, $date){
		$this->db = $db;
		$this->eventid = $eventid;
		$this->uid = $uid;
		$this->date = $date;
		$this->commit();
	}
}
$keyData = new KeyData();
new MovePlayerToSI(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_GET['eventid'], $_GET['uid'], $_GET["date"]);


?>
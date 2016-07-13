<?php

require_once __DIR__  . '/../../Database/Mysql.php';

class ParticipateEvent{
	private $db = null;
	private $uid = 0;
	private $charName = '';
	private $eventid = 0;
	private $note = '';
	private $role = '';
	private $date = '';
	
	private function getRole(){
		switch($this->role){
			case 'DPS' :
				return 1;
				break;
			case 'Tank' :
				return 3;
				break;
			case 'Heal' :
				return 2;
				break;
			case 'Forgot to sign in' :
				return 5;
				break;
			case 'Update message' :
				return $this->db->query('SELECT role FROM calender_event_participants WHERE uid = "'.$this->uid.'" AND eventid = "'.$this->eventid.'"')->fetch()->role;
				break;
			default :
				return 4;
				break;
		}
	}
	
	private function getCharid(){
		return $this->db->query('SELECT charid FROM user_char WHERE charName = "'.$this->charName.'"')->fetch()->charid;
	}
	
	private function getDate(){
		return $this->db->query('SELECT date FROM calender_event WHERE eventid ='.$this->eventid)->fetch()->date;
	}
	
	private function goHome(){
		header('Location: ../../calendar/event/?date='.$this->getDate());
		exit();
	}
	
	private function isConfirmed(){
		if($this->db->query('SELECT uid FROM user WHERE confirmed = 1 AND uid='.$this->uid)->rowCount() != 0)
			return true;
		else 
			return false;
	}
	
	private function hasSignedIn(){
		if($this->db->query('SELECT pid FROM calender_event_participants WHERE uid ='.$this->uid.' AND eventid ='.$this->eventid)->rowCount() != 0)
			return true;
		else
			return false;
	}
	
	private function commit(){
		if($this->isConfirmed()){
			if($this->hasSignedIn())
				$this->db->query('UPDATE calender_event_participants SET charid ='.$this->getCharid().', role = '.$this->getRole().', note = "'.htmlentities($this->note).'" WHERE uid ='.$this->uid.' AND eventid ='.$this->eventid);
			else
				$this->db->query('INSERT INTO calender_event_participants (eventid, charid, role, note, uid, date) VALUES ('.$this->eventid.', '.$this->getCharid().', '.$this->getRole().', "'.htmlentities($this->note).'", '.$this->uid.', "'.$this->date.'")');
		}
		$this->goHome();
	}
	
	public function __construct($db, $uid, $charName, $note, $role, $eventid){
		$this->db = $db;
		$this->uid = $uid;
		$this->charName = $charName;
		$this->note = $note;
		$this->role = $role;
		$this->eventid = $eventid;
		$this->date = date('d.m.y g:i A');
		$this->commit();
	}
}
$keyData = new KeyData();
new ParticipateEvent(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['uid'], $_POST['charName'], $_POST['note'], $_POST['role'], $_POST['eventid']);

?>
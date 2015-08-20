<?php

require_once __DIR__  . '/../../Database/Mysql.php';

class RemovePlayer{
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
	
	public function commit(){
		if ($this->validValues($this->eventid) and $this->validValues($this->uid)){
			$this->db->query('DELETE FROM calender_event_participants WHERE eventid='.$this->eventid.' AND uid ='.$this->uid);
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
new RemovePlayer(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_GET['eventid'], $_GET['uid'], $_GET["date"]);


?>
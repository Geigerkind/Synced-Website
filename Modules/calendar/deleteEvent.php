<?php

require_once __DIR__  . '/../../Database/Mysql.php';

class DeleteEvent{
	private $db = null;
	private $eventid = 0;
	
	public function goHome(){
		header('Location: ../../calendar/?date='.date('01.m.Y'));
		exit();
	}
	
	public function validValues($val){
		if ($val != 0 and $val != null)
			return true;
		return false;
	}
	
	public function commit(){
		if ($this->validValues($this->eventid)){
			$this->db->query('DELETE FROM calender_event WHERE eventid='.$this->eventid);
			$this->db->query('DELETE FROM calender_event_participants WHERE eventid='.$this->eventid);
			$this->goHome();
		}
	}
	
	public function __construct($db, $eventid){
		$this->db = $db;
		$this->eventid = $eventid;
		$this->commit();
	}
}
$keyData = new KeyData();
new DeleteEvent(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_GET['eventid']);


?>
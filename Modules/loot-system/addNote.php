<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class AddNote{
	private $db = null;
	private $note = '';
	private $uid = 0;
	private $cuid = 0;
	private $date = '';
	private $location = '';
	
	private function goHome(){
		header('Location: '.$this->location);
		exit();
	}
	
	private function isOfficer(){
		if($this->db->query('SELECT rank FROM user WHERE uid ='.$this->cuid)->fetch()->rank >= 6)
			return true;
		else
			return false;
	}
	
	private function commit(){
		if(!empty($this->note) AND !empty($this->uid) AND !empty($this->cuid) AND $this->isOfficer())
			$this->db->query('INSERT INTO lc_notes (uid, text, date, creatoruid) VALUES ('.$this->uid.', "'.htmlentities($this->note).'", "'.$this->date.'", '.$this->cuid.')');
		$this->goHome();
	}
	
	public function __construct($db, $note, $uid, $cuid, $location){
		$this->db = $db;
		$this->note = $note;
		$this->uid = $uid;
		$this->cuid = $cuid;
		$this->date = date('d.m.y, g:i A');
		$this->location = $location;
		$this->commit();
	}
}
$keyData = new KeyData();
new AddNote(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)) ,$_POST['note'], $_POST['uid'], $_POST['cuid'], $_POST['location']);
?>
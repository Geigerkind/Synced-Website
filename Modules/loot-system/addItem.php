<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class AddItem{
	private $db = null;
	private $item = '';
	private $uid = 0;
	private $cuid = 0;
	private $date = '';
	private $location = '';
	
	private function goHome(){
		header('Location: '.$this->location);
		exit();
	}
	
	private function isOfficer(){
		if($this->db->query('SELECT rank FROM user WHERE uid ='.$this->cuid)->fetch()->rank >= 5)
			return true;
		else
			return false;
	}
	
	private function getItemEntry($name){
		return $this->db->query('SELECT entry FROM item_template WHERE name = "'.$name.'"')->fetch()->entry;
	}
	
	private function commit(){
		if(!empty($this->item) AND !empty($this->uid) AND !empty($this->cuid) AND $this->isOfficer())
			$this->db->query('INSERT INTO lc_item_history (uid, entry, date, creatoruid) VALUES ('.$this->uid.', '.$this->getItemEntry($this->item).', "'.$this->date.'", '.$this->cuid.')');
		$this->goHome();
	}
	
	public function __construct($db, $item, $uid, $cuid, $location){
		$this->db = $db;
		$this->item = $item;
		$this->uid = $uid;
		$this->cuid = $cuid;
		$this->date = date('d.m.y, g:i A');
		$this->location = $location;
		$this->commit();
	}
}
$keyData = new KeyData();
new AddItem(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)) ,$_POST['item'], $_POST['uid'], $_POST['cuid'], $_POST['location']);
?>
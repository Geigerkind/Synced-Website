<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class DeleteItem{
	private $db = null;
	private $id = 0;
	private $uid = 0;
	private $location = '';
	
	private function isOfficer(){
		if($this->db->query('SELECT rank FROM user WHERE uid ='.$this->uid)->fetch()->rank >= 5)
			return true;
		else
			return false;
	}
	
	private function goHome(){
		header('Location: '.$this->location);
		exit();
	}
	
	private function commit(){
		if(!empty($this->id) AND !empty($this->uid) AND $this->isOfficer())
			$this->db->query('DELETE FROM lc_item_history WHERE id ='.$this->id);
		$this->goHome();
	}
	
	public function __construct($db, $id, $uid, $loc){
		$this->db = $db;
		$this->id = $id;
		$this->uid = $uid;
		$this->location = $loc;
		$this->commit();
	}
}
$keyData = new KeyData();
new DeleteItem(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)) ,$_POST['id'], $_POST['uid'], $_POST['location']);
?>
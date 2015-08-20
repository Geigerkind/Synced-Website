<?php
require_once __DIR__ . '/../../Database/Mysql.php';

class Add {
	private $db = null;
	private $entry = 0;
	private $name = "None";
	private $uid = 0;
	
	private function isNotInDB($entry){
		if($this->db->query('SELECT * FROM item_template WHERE entry ='.$entry)->rowCount() == 0)
			return true;
		else
			return false;
	}
	
	private function isNumber($var){
		return ctype_digit($var);
	}
	
	private function goHome(){
		header('Location: ../../loot-system/');
	}
	
	private function isAuthorized($uid){
		if($this->db->query('SElECT uid FROM user WHERE uid ='.$this->uid.' AND rank >= 5')->rowCount() > 0)
			return true;
		else
			return false;
	}
	
	private function commit(){
		if($this->isNumber($this->entry) AND !empty($this->name) AND $this->isAuthorized($this->uid)){
			if(!$this->isNotinDB($this->entry))
				$this->db->query('DELETE FROM item_template WHERE entry ='.$this->entry);
			$this->db->query('INSERT INTO item_template (entry, name) VALUES ("'.$this->entry.'", "'.$this->name.'")');
		}
		$this->goHome();
	}
	
	public function __construct($db, $entry, $name, $uid){
		$this->db = $db;
		$this->entry = $entry;
		$this->name = $name;
		$this->uid = $uid;
		$this->commit();
	}
}
$keyData = new KeyData();
new Add(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['entry'], $_POST['name'], $_POST['uid']);
?>
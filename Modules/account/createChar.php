<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class CreateChar{
	private $db = null;
	private $name = '';
	private $uid = '';
	private $actionUid = '';
	
	private function userValidation(){
		if($this->uid == $this->actionUid OR $this->db->query('SELECT rank FROM user WHERE uid ='.$this->actionUid)->fetch()->rank >= 6)
			return true;
		else
			return false;
	}
	
	private function goHome(){
		header('Location: ../../account/?uid='.$this->uid);
		exit();
	}
	
	private function validValues(){
		$values = array($this->name, $this->uid, $this->actionUid);
		foreach($values AS $val){
			if(empty($val)){
				return false;
				break;
			}else{
				return true;
			}
		}
	}
	
	private function commit(){
		if($this->validValues() AND $this->userValidation())
			$this->db->query('INSERT INTO user_char (uid, charName, mainChar) VALUES ('.$this->uid.', "'.$this->name.'", 0)');
		$this->goHome();
	}
	
	public function __construct($db, $name, $uid, $actionUid){
		$this->db = $db;
		$this->name = $name;
		$this->uid = $uid;
		$this->actionUid = $actionUid;
		$this->commit();
	}
}
$keyData = new KeyData();
new CreateChar(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['name'], $_POST['uid'], $_POST['actionUid']);

?>
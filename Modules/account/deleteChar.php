<?php
session_start();
require_once __DIR__ . '/../../Database/Mysql.php';
require_once __DIR__ . '/../../Helper/User/UserAgent.php';

class DeleteChar{
	private $uid = 0;
	private $charid = 0;
	private $db = null;
	private $userAgent = null;
	
	
	private function goHome(){
		header('Location: ../../account/?uid='.$this->uid);
		exit();
	}
	
	private function validate(){
		if($this->uid == $this->userAgent->uid){
			if($this->db->query('SELECT * FROM user_char WHERE uid = '.$this->uid.' AND charid ='.$this->charid)->rowCount() > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	private function hasAnotherChar(){
		if($this->db->query('SELECT * FROM user_char WHERE uid = '.$this->uid)->rowCount() > 1){
			return true;
		}else{
			return false;
		}
	}
	
	private function commit(){
		if(($this->validate() OR $this->userAgent->isOfficer()) AND $this->hasAnotherChar()){
			$this->db->query('DELETE FROM user_char WHERE charid ='.$this->charid);
			if($this->db->query('SELECT * FROM user_char WHERE uid ='.$this->uid.' AND mainChar = 1')->rowCount() == 0){
				$this->db->query('UPDATE user_char SET mainChar = 1 WHERE charid = '.$this->db->query('SELECT charid FROM user_char WHERE uid ='.$this->uid.' LIMIT 1')->fetch()->charid);
			}
		}
		$this->goHome();
	}
	
	public function __construct($db, $uid, $auid, $charid){
		$this->db = $db;
		$this->userAgent = new UserAgent($db);
		$this->uid = $uid;
		$this->charid = $charid;
		$this->db = $db;
		$this->commit();
	}
}
$keyData = new KeyData();
new DeleteChar(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['uid'], $_POST['auid'], $_POST['charid']);
?>
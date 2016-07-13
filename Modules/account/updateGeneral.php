<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class UpdateGeneral{
	private $db = null;
	private $name = '';
	private $natio = '';
	private $rank = '';
	private $uid = 0;
	private $actionUid = 0;
	
	private function goHome(){
		header('Location: ../../account/?uid='.$this->uid);
		exit();
	}
	
	private function getRankId(){
		return $this->db->query('SELECT rid FROM ranks WHERE rankname ="'.$this->rank.'"')->fetch()->rid;
	}
	
	private function validValues(){
		if(!empty($this->name) AND !empty($this->natio))
			if(!empty($this->rank))
				return 2;
			else
				return 1;
		else
			return 0;
	}
	
	private function userValidation(){
		if($this->uid == $this->actionUid OR $this->db->query('SELECT rank FROM user WHERE uid ='.$this->actionUid)->fetch()->rank >= 6)
			return true;
		else
			return false;
	}
	
	private function commit(){
		if($this->validValues() >= 1 AND $this->userValidation())
			$this->db->query('UPDATE user SET name = "'.$this->name.'", natio = "'.$this->natio.'" WHERE uid ='.$this->uid);
		if($this->validValues() == 2 AND $this->userValidation())
			$this->db->query('UPDATE user SET rank ='.$this->getRankId().' WHERE uid ='.$this->uid);
		$this->goHome();
	}
	
	public function __construct($db, $name, $natio, $rank, $uid, $actionUid){
		$this->db = $db;
		$this->name = $name;
		$this->natio = $natio;
		$this->rank = $rank;
		$this->uid = $uid;
		$this->actionUid = $actionUid;
		$this->commit();
	}
}
$keyData = new KeyData();
new UpdateGeneral(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['name'], $_POST['natio'], $_POST['rank'], $_POST['uid'], $_POST['actionUid']);
?>
<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class DeleteMedia{
	private $db = null;
	private $actionUid = 0;
	private $vmid = 0;
	private $uid = 0;
	private $id = 0;
	private $media = '';
	
	private function getValues(){
		$q = $this->db->query('SELECT type, id FROM media_media WHERE vmid ='.$this->vmid)->fetch();
		$this->id = $q->id;
		
		switch($q->type){
			case 3 :
				$this->media = 'stream';
				break;
			case 2 :
				$this->media = 'video';
				break;
			case 1 :
				$this->media = 'screenshot';
				break;
		}
		
		$this->uid = $this->db->query('SELECT user FROM media_'.$this->media.' WHERE id ='.$this->id)->fetch()->user;
	}
	
	private function validUser(){
		if($this->uid == $this->actionUid OR $this->db->query('SELECT rank FROM user WHERE uid ='.$this->actionUid)->fetch()->rank >= 5)
			return true;
		else
			return false;
	}
	
	private function validValues(){
		$values = array($this->vmid, $this->actionUid);
		foreach($values AS $val){
			if(empty($val)){
				return false;
				break;
			}else{
				return true;
			}
		}
	}
	
	private function goHome(){
		header('Location: ../../media/');
		exit();
	}
	
	private function commit(){
		if($this->validValues()){
			$this->getValues();
			if($this->validUser()){
				$this->db->query('DELETE FROM media_media WHERE vmid ='.$this->vmid);
				$this->db->query('DELETE FROM media_'.$this->media.' WHERE id ='.$this->id);
			}
		}
		$this->goHome();
	}
	
	public function __construct($db, $actionUid, $vmid){
		$this->db = $db;
		$this->actionUid = $actionUid;
		$this->vmid = $vmid;
		$this->commit();
	}
}
$keyData = new KeyData();
new DeleteMedia(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['actionUid'], $_POST['vmid']);

?>
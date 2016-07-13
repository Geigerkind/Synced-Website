<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class AppEvalState{
	private $db = null;
	private $state = 0;
	private $tid = 0;
	private $uid = 0;
	private $actionUid = 0;
	
	private function update(){
		if($this->db->query('SELECT uid FROM user WHERE rank IN (5,6,7,8) AND uid ='.$this->actionUid)->rowCount() != 0)
			$this->db->query('UPDATE user SET confirmed = '.$this->state.', jgdate = "'.date('d.m.y').'", timestamp = '.time().' WHERE uid = '.$this->uid);
		header('Location: ../../forum/section/thread/?tid='.$this->tid);
		exit();
	}
	
	public function __construct($db, $tid, $uid, $actionUid, $state){
		$this->db = $db;
		$this->tid = $tid;
		$this->uid = $uid;
		$this->actionUid = $actionUid;
		$this->state = $state;
		$this->update();
	}
}
$keyData = new KeyData();
$db = new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
new AppEvalState($db, $_POST['tid'], $_POST['uid'], $_POST['actionUid'], $_POST["submit"]);
?>
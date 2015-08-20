<?php

require_once __DIR__ . '/../../Database/Mysql.php';
require_once __DIR__ . '/../../Helper/User/UserAgent.php';

class deleteThread{
	private $userAgent = null;
	private $db = null;
	private $gtid = null;
	private $tid = null;
	
	private function delete(){
		if($this->userAgent->isOfficer())
			$this->db->query('UPDATE forum_topics SET gtid = 38 WHERE tid = '.$this->tid);
		header('Location: ../../forum/section/?gtid='.$this->gtid);
		exit();
	}
	
	public function __construct($ua, $db, $gtid, $tid){
		$this->userAgent = $ua;
		$this->db = $db;
		$this->gtid = $gtid;
		$this->tid = $tid;
		$this->delete();
	}
}
$keyData = new KeyData();
$db = new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
new deleteThread(new UserAgent($db), $db, $_GET['gtid'], $_GET['tid']);
?>
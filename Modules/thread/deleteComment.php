<?php

require_once __DIR__ . '/../../Database/Mysql.php';
require_once __DIR__ . '/../../Helper/User/UserAgent.php';

class deleteComment{
	private $userAgent = null;
	private $db = null;
	private $cid = null;
	private $tid = null;
	
	private function delete(){
		if($this->userAgent->isOfficer())
			$this->db->query('DELETE FROM forum_topics_comment WHERE cid ='.$this->cid);
		header('Location: ../../forum/section/thread/?tid='.$this->tid);
		exit();
	}
	
	public function __construct($ua, $db, $cid, $tid){
		$this->userAgent = $ua;
		$this->db = $db;
		$this->cid = $cid;
		$this->tid = $tid;
		$this->delete();
	}
}
$keyData = new KeyData();
$db = new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
new deleteComment(new UserAgent($db), $db, $_GET['cid'], $_GET['tid']);
?>
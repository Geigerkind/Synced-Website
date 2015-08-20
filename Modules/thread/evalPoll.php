<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class EvalPoll{
	private $pollid = 0;
	private $argid = 0;
	private $tid = 0;
	private $db = null;
	private $uid = 0;
	
	private function goHome(){
		header('Location: ../../forum/section/thread/?tid='.$this->tid);
		exit();
	}
	
	private function getValues(){
		$this->tid = $_POST['tid'];
		for($i = 1; $i <= $_POST['eval']; $i++){
			if($_POST['poll-checkbox-'.$_POST['poll-arg-'.$i]]){
				$this->argid = $_POST['poll-arg-'.$i];
				break;
			}
		}
	}
	
	private function doDBWork(){
		if(!empty($this->argid)){
			$this->db->query('DELETE a FROM forum_topics_poll_args_voice AS a JOIN forum_topics_poll_args AS b ON a.argid = b.argid JOIN forum_topics_poll AS c ON b.pollid = c.pollid WHERE a.uid = '.$this->uid.' AND c.tid = '.$this->tid);
			$this->db->query('INSERT INTO forum_topics_poll_args_voice (argid, uid) VALUES ('.$this->argid.', '.$this->uid.')');
			$this->goHome();
		}else{
			$this->goHome();
		}
	}
	
	public function __construct($db){
		$this->getValues();
		$this->db = $db;
		$this->uid = $_POST['uid'];
		$this->doDBWork();
	}
}
$keyData = new KeyData();
new EvalPoll(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)));
?>
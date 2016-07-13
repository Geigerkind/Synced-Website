<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class CreateComment{
	private $db = null;
	private $text = '';
	private $uid = 0;
	private $date = '';
	private $vmid = 0;
	
	private function isConfirmed(){
		if($this->db->query('SELECT confirmed FROM user WHERE uid ='.$this->uid)->rowCount() != 0)
			return true;
		else
			return false;
	}
	
	private function goHome(){
		header('Location: ../../media/show/?id='.$this->db->query('SELECT vmid FROM media_comments WHERE uid ='.$this->uid.' AND text = "'.$this->formatText($this->text).'"')->fetch()->vmid);
		exit();
	}
	
	private function validValues(){
		$values = array($this->uid, $this->text);
		foreach($values AS $val){
			if(empty($val)){
				return false;
				break;
			}else{
				return true;
			}
		}
	}
	
	private function ignoreTag($tag, $text){
		if (strpos($text,'['.$tag.']') !== false) {
			$p1 = explode('['.$tag.']', $text);
			$text = '';
			for($f=1; $f<=sizeof($p1); $f++){
				$p2 = explode('[/'.$tag.']', $p1[$f]);
				$p2zero = str_replace('<br />', "", $p2[0]);
				$text = $text.'['.$tag.']'.$p2zero.'[/'.$tag.']'.$p2[1];
			}
			$text = $p1[0].$text;
			$result = str_replace('['.$tag.'][/'.$tag.']', '', $text);
			return $result;
		}else{
			return $text;
		}
	}
	
	private function formatText($text){
		$trimmed = trim($text);
		$html = htmlentities($trimmed);
		$result = nl2br($html);
		
		$result = $this->ignoreTag('table', $result);
		$result = $this->ignoreTag('ul', $result);
		$result = $this->ignoreTag('ol', $result);
		
		return $result;
	}
	
	private function commit(){
		if($this->validValues() AND $this->isConfirmed())
			$this->db->query('INSERT INTO media_comments (uid, date, text, vmid) VALUES ('.$this->uid.', "'.$this->date.'", "'.$this->formatText($this->text).'", '.$this->vmid.')');
			$this->db->query('INSERT INTO latest_posts (type, refid, permission) VALUES (1, '.($this->vmid).', 1)');
		$this->goHome();
	}
	
	public function __construct($db, $uid, $vmid, $text){
		$this->db = $db;
		$this->uid = $uid;
		$this->vmid = $vmid;
		$this->text = $text;
		$this->date = date('d.m.y g:i A');
		$this->commit();
	}
}
$keyData = new KeyData();
new CreateComment(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['uid'], $_POST['vmid'], $_POST['text']);

?>
<?php

require_once __DIR__  . '/../../Database/Mysql.php';

class EditMedia{
	private $db = null;
	private $title = '';
	private $text = '';
	private $uid = 0;
	private $actionUid = 0;
	private $vmid = 0;
	
	private function validValues(){
		$values = array($this->uid, $this->actionUid, $this->title, $this->text, $this->vmid);
		foreach($values AS $val){
			if(empty($val)){
				return false;
				break;
			}else{
				return true;
			}
		}
	}
	
	private function formatValue($val){
		return htmlentities($val);
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
	
	private function goHome(){
		header('Location: ../../media/show/?id='.$this->vmid);
		exit();
	}
	
	private function validUser(){
		if($this->uid == $this->actionUid OR $this->db->query('SELECT rank FROM user WHERE uid ='.$this->actionUid)->fetch()->rank >= 5)
			return true;
		else
			return false;
	}
	
	private function commit(){
		if($this->validUser() AND $this->validValues())
			$this->db->query('UPDATE media_screenshot SET title = "'.$this->formatValue($this->title).'", descr = "'.$this->formatText($this->text).'" WHERE id ='.$this->db->query('SELECT id FROM media_media WHERE vmid ='.$this->vmid)->fetch()->id);
		$this->goHome();
	}
	
	public function __construct($db, $title, $text, $uid, $actionUid, $vmid){
		$this->db = $db;
		$this->title = $title;
		$this->text = $text;
		$this->uid = $uid;
		$this->actionUid = $actionUid;
		$this->vmid = $vmid;
		$this->commit();
	}
}
$keyData = new KeyData();
new EditMedia(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['title'], $_POST['text'], $_POST['uid'], $_POST['actionUid'], $_POST['vmid']);

?>
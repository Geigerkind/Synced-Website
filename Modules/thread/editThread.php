<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class EditThread{
	private $db = null;
	private $title = '';
	private $text = '';
	private $tid = 0;
	private $cid = 0;
	private $sticky = 0;
	
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
		$html = htmlentities($trimmed, ENT_COMPAT, $charset);
		$result = nl2br($html);
		
		$result = $this->ignoreTag('table', $result);
		$result = $this->ignoreTag('ul', $result);
		$result = $this->ignoreTag('ol', $result);
		
		return $result;
	}
	
	private function goHome(){
		header('Location: ../../forum/section/thread/?tid='.$this->tid);
		exit();
	}
	
	private function edit(){
		if(!empty($this->text) AND !empty($this->title)){
			$this->db->query('UPDATE forum_topics_comment SET title ="'.htmlentities($this->title, ENT_COMPAT, $charset).'", text="'.$this->formatText($this->text).'" WHERE cid ='.$this->cid);
			if($this->cid == $this->db->query('SELECT min(cid) AS cid FROM forum_topics_comment WHERE tid ='.$this->tid)->fetch()->cid){
				if ($this->sticky == 1){
					$this->db->query('UPDATE forum_topics SET title ="'.htmlentities($this->title, ENT_COMPAT, $charset).'", descr ="'.$this->formatText($this->text).'", sticky = 1 WHERE tid ='.$this->tid);
				}else{
					$this->db->query('UPDATE forum_topics SET title ="'.htmlentities($this->title, ENT_COMPAT, $charset).'", descr ="'.$this->formatText($this->text).'", sticky = 0 WHERE tid ='.$this->tid);
				}
			}
		}
		$this->goHome();
	}
	
	public function __construct($db, $title, $text, $tid, $cid){
		$this->db = $db;
		$this->title = $title;
		$this->text = $text;
		$this->tid = $tid;
		$this->cid = $cid;
		if (isset($_POST["sticky"]))
			$this->sticky = 1;
		$this->edit();
	}
}
$keyData = new KeyData();
new EditThread(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['title'], $_POST['text'], $_POST['tid'], $_POST['cid']);

?>
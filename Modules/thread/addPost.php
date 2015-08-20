<?php
	
require_once __DIR__ . '/../../Database/Mysql.php';

class AddPost{
	private $db = null;
	private $title = '';
	private $text = '';
	private $uid = null;
	private $tid = null;
	private $date = '';
	
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
	
	private function getLocation(){
		$sql = 'SELECT cid FROM forum_topics_comment WHERE tid = '.$this->tid.' ORDER BY cid DESC';
		$comments = $this->db->query($sql)->rowCount();
		$cid = $this->db->query($sql)->fetch();
		$page = ceil($comments / 10);
		return header('Location: ../../forum/section/thread/?tid='.$this->tid.'&page='.$page.'#comment-'.$cid->cid);
	}
	
	private function preventDoublePosting(){
		if($this->db->query('SELECT cid FROM forum_topics_comment WHERE tid ='.$this->tid.' AND uid ='.$this->uid.' AND date ="'.$this->date.'" AND text ="'.$this->formatText($this->text).'"')->rowCount() == 0)
			return true;
	}
	
	private function post(){
		if(!empty($this->text) AND !empty($this->title) AND $this->preventDoublePosting()){
			$this->db->query('INSERT INTO forum_topics_comment (tid, uid, title, text, date) VALUES ('.$this->tid.', '.$this->uid.',"'.htmlentities($this->title, ENT_COMPAT, $charset).'", "'.$this->formatText($this->text).'", "'.$this->date.'")');
			$this->db->query('INSERT INTO latest_posts (type, refid, permission) VALUES (0, "'.$this->db->query('SELECT cid FROM forum_topics_comment WHERE tid ="'.$this->tid.'" AND uid ='.$this->uid.' AND date ="'.$this->date.'" AND text ="'.$this->formatText($this->text).'"')->fetch()->cid.'",1)');
		}
		$this->getLocation();
		exit();
	}
	
	public function __construct($db, $title, $text, $uid, $tid){
		$this->db = $db;
		$this->title = $title;
		$this->text = $text;
		$this->uid = $uid;
		$this->tid = $tid;
		$this->date = date('d.m.y, g:i A');
		$this->post();
	}
}
$keyData = new KeyData();
new addPost(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['title'], $_POST['text'], $_POST['uid'], $_POST['tid']);
?>
<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class AddThread{
	private $db = null;
	private $title = '';
	private $text = '';
	private $uid = null;
	private $gtid = null;
	private $tid = null;
	private $date = '';
	private $poll = false;
	private $duration = '';
	private $args = array();
	
	private function getValues(){
		if(isset($_POST['poll'])){
			$this->poll = 1;
			$this->duration = $_POST['poll-duration'];
			for($i = 1; $i <= 5; $i++){
				if(!empty($_POST['poll-arg-'.$i]))
					$this->args[$i] = $_POST['poll-arg-'.$i];
			}
		}else{
			$this->poll = 0;
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
		$html = htmlentities($trimmed, ENT_COMPAT, $charset);
		$result = nl2br($html);
		
		$result = $this->ignoreTag('table', $result);
		$result = $this->ignoreTag('ul', $result);
		$result = $this->ignoreTag('ol', $result);
		
		return $result;
	}
	
	private function goHome(){
		header('Location: ../../forum/section/?gtid='.$this->gtid);
		exit();
	}
	
	private function preventDoublePost(){
		if($this->db->query('SELECT tid FROM forum_topics WHERE uid ='.$this->uid.' AND title ="'.htmlentities($this->title, ENT_COMPAT, $charset).'" AND date ="'.$this->date.'"')->rowCount() == 0)
			return true;
	}
	
	private function post(){
		if(!empty($this->text) AND !empty($this->title)){
			if($this->preventDoublePost()){
				$this->db->query('INSERT INTO forum_topics (gtid, uid, title, date, descr, poll) VALUES ('.$this->gtid.', '.$this->uid.', "'.htmlentities($this->title, ENT_COMPAT, $charset).'", "'.$this->date.'", "'.$this->formatText($this->text).'", '.$this->poll.')');
				$gtid = $this->gtid;
				$var = $this->db->query('SELECT tid FROM forum_topics WHERE gtid ='.$this->gtid.' AND uid = '.$this->uid.' AND date = "'.$this->date.'"')->fetch();
				$this->tid = $var->tid;
				$this->db->query('INSERT INTO forum_topics_comment (tid, uid, title, text, date) VALUES ('.$this->tid.', '.$this->uid.', "'.htmlentities($this->title, ENT_COMPAT, $charset).'", "'.$this->formatText($this->text).'", "'.$this->date.'")');
				if($this->poll){
					$this->db->query('INSERT INTO forum_topics_poll (tid, duration, date) VALUES ('.$this->tid.', '.$this->duration.', "'.date('d.m.y').'")');
					$pvar = $this->db->query('SELECT pollid FROM forum_topics_poll WHERE tid= '.$this->tid)->fetch();
					foreach($this->args AS $value){
						if(!empty($value))
							$this->db->query('INSERT INTO forum_topics_poll_args (pollid, arg) VALUES ('.$pvar->pollid.', "'.$value.'")');
					}
				}
				$this->db->query('INSERT INTO latest_posts (type, refid, permission) VALUES (0, "'.$this->db->query('SELECT cid FROM forum_topics_comment WHERE tid ="'.$this->tid.'" AND uid ='.$this->uid.' AND date ="'.$this->date.'" AND text ="'.$this->formatText($this->text).'"')->fetch()->cid.'", '.$this->db->query('SELECT permission FROM forum_section_topics a JOIN forum_section b ON a.sid = b.sid WHERE a.gtid ='.$gtid)->fetch()->permission.')');
				header('Location: ../../forum/section/thread/?tid='.$this->tid);
				exit();
			}else{
				header('Location: ../../forum/section/thread/?tid='.$this->db->query('SELECT tid FROM forum_topics WHERE gtid ='.$this->gtid.' AND uid = '.$this->uid.' AND date = "'.$this->date.'"')->fetch()->tid);
				exit();
			}
		}else{
			$this->goHome();
		}
	}
	
	public function __construct($db, $title, $text, $uid, $gtid){
		$this->db = $db;
		$this->title = $title;
		$this->text = $text;
		$this->uid = $uid;
		$this->gtid = $gtid;
		$this->date = date('d.m.y, g:i A');
		$this->getValues();
		$this->post();
	}
}
$keyData = new KeyData();
new addThread(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['title'], $_POST['text'], $_POST['uid'], $_POST['gtid']);

?>
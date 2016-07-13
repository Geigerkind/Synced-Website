<?php

session_start();
require_once __DIR__ . '/../../Database/Mysql.php';

class NewAccount{
	private $db = null;
	private $err = '';
	private $tid = 0;
	private $uid = 0;
	private $uname = '';
	private $charname = '';
	private $pass = '';
	private $pass2 = '';
	private $class = '';
	private $race = '';
	private $text = '';
	private $date = '';
	private $check = false;
	
	private function goHome($bool = false){
		if($bool)
			header('Location: ../../forum/section/thread/?tid='.$this->tid);
		else
			header('Loaction: ../../apply/?err='.$this->err);
		exit();
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
	
	private function testValues(){
		if($this->check){
		if(!empty($this->uname) AND !empty($this->pass) AND !empty($this->charname) AND !empty($this->text)){
			if($this->pass == $this->pass2){
				if($this->db->query('SELECT uid FROM user WHERE name = "'.$this->uname.'"')->rowCount() == 0){
					$this->pass = md5($this->pass);
					return true;
				}else{
					$this->err = 'Username is already taken.';
					return false;
				}
			}else{
				$this->err = 'The passwords do not match.';
				return false;
			}
		}else{
			$this->err = 'You did not fill all forms.';
			return false;
		}
		}else{
			$this->err = 'You did not accept our Rules.';
			return false;
		}
	}
	
	private function commit(){
		if($this->testValues()){
			$this->db->query('INSERT INTO user (name, pass, jgdate, timestamp, rank) VALUES ("'.$this->uname.'", "'.$this->pass.'", "'.date('d.m.y').'", '.time().', 2)');
			$this->uid = $this->db->query('SELECT uid FROM user WHERE name = "'.$this->uname.'" AND pass = "'.$this->pass.'"')->fetch()->uid;
			$this->db->query('INSERT INTO user_char (uid, charName, race, class, mainchar) VALUES ('.$this->uid.', "'.$this->charname.'", "'.$this->race.'", "'.$this->class.'", 1)');
			$this->db->query('INSERT INTO forum_topics (gtid, uid, title, date, descr) VALUES (37, '.$this->uid.', "Application of '.htmlentities($this->uname).'", "'.$this->date.'", "'.$this->formatText($this->text).'")');
			$this->tid = $this->db->query('SELECT tid FROM forum_topics WHERE title = "Application of '.htmlentities($this->uname).'"')->fetch()->tid;
			$this->db->query('INSERT INTO forum_topics_comment (tid, uid, title, text, date) VALUES ('.$this->tid.', '.$this->uid.', "Application of '.htmlentities($this->uname).'", "'.$this->formatText($this->text).'", "'.$this->date.'")');
			$_SESSION['user'] = $this->uid;
			setCookie('user', $this->uid, time() + 60*60*24*7);
			$this->db->query('INSERT INTO latest_posts (type, refid, permission) VALUES (0, "'.$this->db->query('SELECT cid FROM forum_topics_comment WHERE tid ="'.$this->tid.'" AND uid ='.$this->uid.' AND date ="'.$this->date.'" AND text ="'.$this->formatText($this->text).'"')->fetch()->cid.'", '.$this->db->query('SELECT permission FROM forum_section_topics a JOIN forum_section b ON a.sid = b.sid WHERE a.gtid =37')->fetch()->permission.')');
			$this->goHome(true);
		}else{
			$this->goHome(false);
		}
	}
	
	public function __construct($db, $uname, $charname, $pass, $pass2, $text, $race, $class, $check){
		$this->db = $db;
		$this->uname = $uname;
		$this->charname = $charname;
		$this->pass = $pass;
		$this->pass2 = $pass2;
		$this->text = $text;
		$this->race = $race;
		$this->class = $class;
		$this->check = $check;
		$this->date = date('d.m.y, g:i A');
		$this->commit();
	}
}
$keyData = new KeyData();
new NewAccount(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['uname'], $_POST['charname'], $_POST['pass'], $_POST['pass2'], $_POST['text'], $_POST['race'], $_POST['class'], $_POST['check']);
?>
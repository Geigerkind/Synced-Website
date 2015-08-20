<?php

require_once __DIR__  . '/../../Database/Mysql.php';

class CreateEvent{
	private $db = null;
	private $date = '';
	private $title = '';
	private $uid = 0;
	private $img = '';
	private $time = '';
	private $text = '';
	
	private function getImg(){
		switch($this->img){
			case 'Molten Core':
				return 1;
				break;
			case 'Black Wing Lair' :
				return 2;
				break;
			case 'Onyxia\'s Lair' :
				return 3;
				break;
			default :
				return 0;
				break;
		}
	}
	
	private function isContentAvailable(){
		if($this->db->query('SELECT eventid FROM calender_event WHERE date = "'.$this->date.'"')->rowCount() != 0)
			return true;
		else
			return false;
	}
	
	private function goHome(){
		header('Location: ../../calendar/event/?date='.$this->date);
		exit();
	}
	
	private function validValues(){
		if(!empty($this->title) AND !empty($this->text) AND !empty($this->date) AND !empty($this->uid) AND !empty($this->time))
			return true;
		else
			return false;
	}
	
	private function commit(){
		if(!$this->isContentAvailable() AND $this->validValues())
			$this->db->query('INSERT INTO calender_event (title, description, date, img, time, creator, timestamp) VALUES ("'.htmlentities($this->title, ENT_COMPAT, $charset).'", "'.htmlentities($this->text, ENT_COMPAT, $charset).'", "'.$this->date.'", "'.$this->getImg().'", "'.htmlentities($this->time, ENT_COMPAT, $charset).'", '.$this->uid.', '.strtotime($this->date).')');
		$this->goHome();
	}
	
	public function __construct($db, $date, $title, $uid, $img, $time, $text){
		$this->db = $db;
		$this->date = $date;
		$this->title = $title;
		$this->uid = $uid;
		$this->img = $img;
		$this->time = $time;
		$this->text = $text;
		$this->commit();
	}
}
$keyData = new KeyData();
new CreateEvent(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['date'], $_POST['title'], $_POST['uid'], $_POST['img'], $_POST['time'], $_POST['text']);

?>
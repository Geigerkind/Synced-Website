<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class CreateStream{
	private $db = null;
	private $title = "";
	private $twname = "";
	private $descr = "";
	private $uid = 0;
	private $date = "";
	
	private function goHome(){
		header('Location: ../../media/');
		exit();
	}
	
	private function validValues(){
		if(!empty($this->title) and !empty($this->twname) and !empty($this->descr) and $this->uid != 0)
			return true;
		return false;
	}
	
	private function validTWName(){
		$data = json_decode(file_get_contents("https://api.twitch.tv/kraken/streams/".$this->twname));
		if($data->status != 404)
			return true;
		return false;
	}
	
	private function streamExists(){
		if($this->db->query('SELECT * FROM media_stream WHERE link = "'.$this->twname.'"')->rowCount() == 0)
			return true;
		return false;
	}
	
	private function commit(){
		if($this->validValues() and $this->validTWName() and $this->streamExists()){
			$this->db->query('INSERT INTO media_stream (title, link, user, descr, date) VALUES ("'.$this->title.'", "'.$this->twname.'", '.$this->uid.', "'.$this->descr.'", "'.$this->date.'")');
			$id = $this->db->query('SELECT id FROM media_stream WHERE link ="'.$this->twname.'"')->fetch()->id;
			$this->db->query('INSERT INTO media_media (type, id) VALUES (3, "'.$id.'")');
			$this->db->query('INSERT INTO latest_posts (type, refid, permission) VALUES (1, "'.$this->db->query('SELECT vmid FROM media_media WHERE type=3 AND id ="'.$id.'"')->fetch()->vmid.'",0)');
		}
		$this->goHome();
	}
	
	public function __construct($db, $title, $twname, $descr, $uid){
		$this->db = $db;
		$this->title = $title;
		$this->twname = $twname;
		$this->descr = $descr;
		$this->uid = $uid;
		$this->date = date('d.m.y');
		$this->commit();
	}
}
$keyData = new KeyData();
new CreateStream(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['title'], $_POST['twname'], $_POST['descr'], $_POST["uid"]);


?>
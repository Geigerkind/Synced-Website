<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class CreateVideo{
	private $db = null;
	private $link = "";
	private $descr = "";
	private $uid = 0;
	private $date = "";
	private $title = "";
	
	private function goHome(){
		header('Location: ../../media/');
		exit();
	}
	
	private function validValues(){
		if(!empty($this->link) and !empty($this->descr) and !empty($this->title) and $this->uid != 0)
			return true;
		return false;
	}
	
	private function validLink(){
		$videoJson = "http://www.youtube.com/oembed?url=".$this->link."&format=json";
		$headers = get_headers($videoJson);
		$code = substr($headers[0], 9, 3);
		if ($code != "404")
			return true;
		return false;
	}
	
	private function LinkExist(){
		if($this->db->query('SELECT * FROM media_video WHERE link ="'.$this->link.'"')->rowCount() == 0)
			return true;
		return false;
	}
	
	private function commit(){
		if($this->validValues() and $this->validLink()){
			$id = explode("watch?v=", $this->link);
			$this->link = $id[1];
			if ($this->LinkExist()){
				$this->db->query('INSERT INTO media_video (title, link, user, descr, date) VALUES ("'.$this->title.'", "'.$this->link.'", '.$this->uid.', "'.$this->descr.'", "'.$this->date.'")');
				$id = $this->db->query('SELECT id FROM media_video WHERE link ="'.$this->link.'"')->fetch()->id;
				$this->db->query('INSERT INTO media_media (type, id) VALUES (2, "'.$id.'")');
				$this->db->query('INSERT INTO latest_posts (type, refid, permission) VALUES (1, "'.$this->db->query('SELECT vmid FROM media_media WHERE type=1 AND id ="'.$id.'"')->fetch()->vmid.'",0)');
			}
		}
		$this->goHome();
	}
	
	public function __construct($db, $link, $descr, $uid, $title){
		$this->db = $db;
		$this->link = $link;
		$this->descr = $descr;
		$this->uid = $uid;
		$this->date = date('d.m.y');
		$this->title = $title;
		$this->commit();
	}
}
$keyData = new KeyData();
new CreateVideo(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['url'], $_POST['descr'], $_POST["uid"], $_POST["title"]);


?>
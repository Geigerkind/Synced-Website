<?php

require_once __DIR__ . '/../../Database/Mysql.php';
require_once __DIR__ . '/../../External/SimpleImage/simpleImage.php';

class CreateScreenshot{
	private $db = null;
	private $file = null;
	private $descr = "";
	private $uid = 0;
	private $date = "";
	private $title = "";
	private $maxID = 0;
	private $targetDIR = "../../Database/img/media/";
	
	private function goHome(){
		header('Location: ../../media/');
		exit();
	}
	
	private function validValues(){
		if(!empty($this->descr) and !empty($this->title) and $this->uid != 0 and file_exists($this->file))
			return true;
		return false;
	}
	
	private function getMaxId(){
		return $this->db->query('SELECT max(id) AS id FROM media_screenshot')->fetch()->id;
	}
	
	private function setUpImage(){
		$image = new SimpleImage();
		$image->load($this->file);
		$image->save($this->targetDIR.$this->maxID."_large.png");
		$image->resize(150, 150);
		$image->save($this->targetDIR.$this->maxID."_small.png");
	}
	
	private function commit(){
		if($this->validValues()){
			$this->setUpImage();
			$this->db->query('INSERT INTO media_screenshot (title, link, user, descr, date) VALUES ("'.$this->title.'", "'.$this->maxID.'", '.$this->uid.', "'.$this->descr.'", "'.$this->date.'")');
			$id = $this->db->query('SELECT id FROM media_screenshot WHERE link ="'.$this->maxID.'"')->fetch()->id;
			$this->db->query('INSERT INTO media_media (type, id) VALUES (1, "'.$id.'")');
			$this->db->query('INSERT INTO latest_posts (type, refid,permission) VALUES (1, "'.$this->db->query('SELECT vmid FROM media_media WHERE type=1 AND id ="'.$id.'"')->fetch()->vmid.'",0)');
		}
		$this->goHome();
	}
	
	public function __construct($db, $file, $descr, $uid, $title){
		$this->db = $db;
		$this->file = $file;
		$this->descr = $descr;
		$this->uid = $uid;
		$this->date = date('d.m.y');
		$this->title = $title;
		$this->maxID = $this->getMaxId() + 1;
		$this->commit();
	}
}
$keyData = new KeyData();
new CreateScreenshot(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_FILES['file']["tmp_name"], $_POST['descr'], $_POST["uid"], $_POST["title"]);


?>
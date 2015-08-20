<?php

require_once __DIR__ . '/../../Database/Mysql.php';
require_once __DIR__ . '/../../External/SimpleImage/simpleImage.php';

class UploadImage{
	private $db = null;
	private $uid = 0;
	private $target_dir = '../../Database/img/User/';
	private $image = null;
	
	public function goHome(){
		header('Location: ../../account/?uid='.$this->uid);
		exit();
	}
	
	public function process(){
		if(!empty($_FILES["uploadFile"]) AND !empty($this->uid)){
			$this->image->load($_FILES["uploadFile"]["tmp_name"]);
			$this->image->resize(100, 100);
			$this->image->save($this->target_dir.$this->uid.".png");
			$this->db->query('UPDATE user SET img = '.$this->uid.' WHERE uid ='.$this->uid);
		}
		$this->goHome();
	}
	
	public function __construct($db, $uid, $image){
		$this->db = $db;
		$this->uid = $uid;
		$this->image = $image;
		$this->process();
	}
}
$keyData = new KeyData();
new UploadImage(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['uid'], new SimpleImage());
?>
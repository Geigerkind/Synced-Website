<?php
/*
* Synced Class File
*
* This file provides the Class information
* for a simple shoutbox post.
*
* @requires Database Class
*
* @author Albea <https://bitbucket.org/Albea>
* @license <https://synced-kronos.net/license>
* @package Helper/Util/ShoutboxPost
* @category Util
*
* @version 0.1.0 (Synced)
*/
require_once __DIR__ . '/../Database/Mysql.php';

class ShoutboxPost{
	private $db = null;
	private $uid = 0;
	private $message = '';
	private $date = '';
	private $path = '';
	
	private function goHome(){
		header('Location: '.$this->path.'#shoutbox');
	}
	
	private function commit(){
		if($this->preventDoublePosting())
			$this->db->query('INSERT INTO shoutbox (uid, date, text) VALUES ('.$this->uid.', "'.$this->date.'", "'.htmlentities($this->message, ENT_COMPAT, $charset).'")');
		$this->goHome();
	}
	
	private function preventDoublePosting(){
		if($this->db->query('SELECT gid FROM shoutbox WHERE uid ='.$this->uid.' AND date ="'.$this->date.'" AND text ="'.htmlentities($this->message, ENT_COMPAT, $charset).'"')->rowCount() == 0)
			return true;
	}
	
	public function __construct($db, $message, $path, $uid){
		$this->db = $db;
		$this->uid = $uid;
		$this->message = $message;
		$this->date = date('d.m, g:i A');
		$this->path = $path;
		$this->commit();
	}
}
$keyData = new KeyData();
new ShoutboxPost(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['text'], $_POST['path'], $_POST['uid']);

?>
<?php

require_once __DIR__  . '/../../Database/Mysql.php';

class CronJob{
	private $db = null;
	private $link = array();
	private $status = array();
	private $viewer = array();
	private $game = array();
	private $views = array();
	private $follower = array();
	private $pic = array();
	private $data1 = array();
	private $data2 = array();
	
	private function isEmptyOrZero($var){
		if(!empty($val) AND $val != 0)
			return false;
		else 
			return true;
	}
	
	private function validValues($arr){
		foreach($arr AS $val){
			if(!$this->isEmptyOrZero($val)){
				return false;
				break;
			}else{
				return true;
			}
		}
	}
	
	private function getRequiredValues(){
		$i = 0;
		foreach($this->db->query('SELECT link FROM media_stream a JOIN user b ON a.user = b.uid WHERE b.confirmed = 1') AS $val){
			$this->link[$i] = $val->link;
			$this->data1[$val->link] = json_decode(file_get_contents("https://api.twitch.tv/kraken/streams/".$val->link));
			$this->data2[$val->link] = json_decode(file_get_contents("https://api.twitch.tv/kraken/channels/".$val->link));
			$i++;
		}
	}
	
	private function printValues($i){
		print $this->link[$i];
		print "<br />";
		print $this->status[$i];
		print "<br />";
		print $this->viewer[$i];
		print "<br />";
		print $this->game[$i];
		print "<br />";
		print $this->views[$i];
		print "<br />";
		print $this->follower[$i];
		print "<br />";
		print $this->pic[$i];
		print "<br />";
		print "<hr />";
		print "<br />";
	}
	
	private function commit(){
		$this->getRequiredValues();
		for($i = 0; $i < sizeOf($this->link); $i++){
			if($this->validValues(array($this->data2[$this->link[$i]]->views, $this->data2[$this->link[$i]]->followers))){
				if($this->data1[$this->link[$i]]->stream)
					$this->status[$i] = 'Online';
				else
					$this->status[$i] = 'Offline';
				if($this->data1[$this->link[$i]]->stream->viewers == null)
					$this->viewer[$i] = 0;
				else
					$this->viewer[$i] = $this->data1[$this->link[$i]]->stream->viewers;
				if($this->data2[$this->link[$i]]->game == null)
					$this->game[$i] = 'World of Warcraft';
				else
					$this->game[$i] = $this->data2[$this->link[$i]]->game;
				$this->views[$i] = $this->data2[$this->link[$i]]->views;
				$this->follower[$i] = $this->data2[$this->link[$i]]->followers;
				$this->pic[$i] = $this->data2[$this->link[$i]]->logo;
				$this->db->query('UPDATE media_stream SET tempStatus = "'.$this->status[$i].'", tempViewer = '.$this->viewer[$i].', tempGame = "'.$this->game[$i].'", tempViews = '.$this->views[$i].', tempFollower = '.$this->follower[$i].', tempPic = "'.$this->pic[$i].'" WHERE link = "'.$this->link[$i].'"');
			}
			$this->printValues($i);
		}
	}
	
	public function __construct($db){
		$this->db = $db;
		$this->commit();
	}
}
$keyData = new KeyData();
new CronJob(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)));

?>
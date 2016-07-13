<?php
/*
* Synced Class File
*
* This file provides the Class information
* for a simple UserAgent.
*
* @requires Database Class
*
* @author Albea <https://bitbucket.org/Albea>
* @license <https://synced-kronos.net/license>
* @package Helper/UserAgent
* @category Helper
*
* @version 0.1.0 (Synced)
*/

class UserAgent {
	public $uid = 0;
	public $name = '';
	public $rank = -1;
	public $rankName = '';
	public $img = '';
	public $jgdate = '';
	public $natio = '';
	public $confirmed = 0;
	public $character = array();
	
	private $db = null;
	
	/*
	* Evaluates Posts
	*/
	private function evalPosts(){
		if($this->isLoggedIn())
			$this->db->query('Update user SET posts = '.$this->db->query('SELECT cid FROM forum_topics_comment WHERE uid = '.$this->uid)->rowCount().', lastactive = '.time().' WHERE uid = '.$this->uid);
	}
	/*
	* @return boolean
	*/
	public function isConfirmed(){
		if($this->confirmed == 1)
			return true;
		return false;
	}
	
	/*
	* @return boolean
	*/
	public function isLoggedIn(){
		if(!empty($_COOKIE['user']) OR !empty($_SESSION['user'])){
			if(!empty($_COOKIE['user'])){
				$this->uid = $_COOKIE['user'];
			}else{
				$this->uid = $_SESSION['user'];
				setCookie('user', $this->uid, time() + 60*60*24*7);
			}
			return true;
		}
		return false;
	}
	
	/*
	* @return boolean
	*/
	public function isOfficer(){
		if($this->rank >= 6)
			return true;
		return false;
	}
	
	/*
	* @return boolean
	*/
	public function isClassLeader(){
		if($this->rank >= 5)
			return true;
		return false;
	}
	
	/*
	* Returns the user's current guild state.
	*
	* @return string
	*/
	public function state(){
		switch($this->confirmed){
			case 0 :
				return 'On Hold';
				break;
			case 1 :
				return 'Confirmed';
				break;
			case 2 : 
				return 'Declined';
				break;
			case 3 : 
				return 'Kicked';
				break;
			default:
				return 'Unknown';
				break;
		}
	}
	
	/*
	* Saves user Information in Object Variables
	*/
	protected function getUserInformation(){
		if($this->isLoggedIn()){
			$sql = 'SELECT * FROM user JOIN ranks ON user.rank = ranks.rid WHERE uid = '.$this->uid;
			$result = $this->db->query($sql)->fetch();
				$this->confirmed = $result->confirmed;
				$this->name = $result->name;
				$this->rank = $result->rank;
				$this->rankName = $result->rankname;
				$this->img = $result->img;
				$this->jgdate = $result->jgdate;
				$this->natio = $result->natio;
				
				if(!$this->isConfirmed()){
					$this->rank = -1;
					$this->rankName = $this->state();
				}
		}
	}
	
	/*
	* Saves character Information in an array
	*/
	protected function getCharacterInformation(){
		if($this->isLoggedIn()){
			$sql = 'SELECT * FROM user_char WHERE uid = '.$this->uid;
			$i = 0;
			foreach($this->db->query($sql) AS $obj){
				$this->character[$i] = $obj;
				$i++;
			}
		}
	}
	
	/*
	* Returns the Object of the main Character. If it returns null, no main Character are found.
	*
	* @return int or null
	*/
	public function mainChar(){
		for($i = 0; $i < sizeOf($this->character); $i++){
			if($this->character[$i]->mainChar){
				return $this->character[$i];
				break;
			}
		}
	}
	
	public function hasAccount(){
		if($this->uid != 0)
			return true;
		else
			return false;
	}
	
	public function __construct($db){
		$this->db = $db;
		$this->getUserInformation();
		$this->getCharacterInformation();
		$this->evalPosts();
	}
}
?>
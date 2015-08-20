<?php

require_once __DIR__ . '/../../Database/Mysql.php';

class UpdateChar{
	private $db = null;
	private $charid = 0;
	private $uid = 0;
	private $actionUid = 0;
	private $charName = '';
	private $race = '';
	private $class = '';
	private $spec = '';
	private $specLink = '';
	private $level = 0;
	private $prof1 = '';
	private $prof2 = '';
	private $prof1Skill = '';
	private $prof2Skill = '';
	private $mainChar = 1;
	private $curChar = '';
	
	private function userValidation(){
		if($this->uid == $this->actionUid OR $this->db->query('SELECT rank FROM user WHERE uid ='.$this->actionUid)->fetch()->rank >= 5)
			return true;
		else
			return false;
	}
	
	private function mainCharProcessing(){
		if($this->mainChar OR $this->mainChar == 'on')
			$this->mainChar = 1;
		else
			$this->mainChar = 0;
		if($this->db->query('SELECT charid FROM user_char WHERE uid ='.$this->uid)->rowCount() > 1 AND !$this->mainChar){
			$this->db->query('UPDATE user_char SET mainChar = 1 WHERE charid ='.$this->db->query('SELECT charid FROM user_char WHERE uid ='.$this->uid.' AND charid !='.$this->charid.' LIMIT 1')->fetch()->charid);
			$this->db->query('UPDATE user_char SET mainChar = 0 WHERE charid ='.$this->charid.' AND uid ='.$this->uid);
		}elseif($this->mainChar){
			$this->db->query('UPDATE user_char SET mainChar = 0 WHERE uid ='.$this->uid);
			$this->db->query('UPDATE user_char SET mainChar = 1 WHERE charid ='.$this->charid.' AND uid ='.$this->uid);
		}else{
			$this->db->query('UPDATE user_char SET mainChar = 1 WHERE charid ='.$this->charid.' AND uid = '.$this->uid);
		}
	}
	
	private function goHome(){
		if(!empty($this->curChar))
			header('Location: ../../account/?uid='.$this->uid.'&char='.$this->curChar);
		else
			header('Location: ../../account/?uid='.$this->uid);
		exit();
	}
	
	private function validChar(){
		if($this->db->query('SELECT charid FROM user_char WHERE charid ='.$this->charid)->rowCount() != 0)
			return true;
		else
			return false;
	}
	
	private function validValues(){
		$values = array($this->uid, $this->actionUid, $this->charName, $this->race, $this->class, $this->spec, $this->specLink, $this->level, $this->prof1, $this->prof2, $this->prof1Skill, $this->prof2Skill, $this->charid);
		foreach($values AS $val){
			if(empty($val)){
				return false;
				break;
			}else{
				return true;
			}
		}
	}
	
	private function commit(){
		if($this->validValues() AND $this->userValidation() AND $this->validChar()){
			$this->db->query('UPDATE user_char SET charName = "'.$this->charName.'", race = "'.$this->race.'", class = "'.$this->class.'", spec = "'.$this->spec.'", specLink = "'.$this->specLink.'", level = '.$this->level.', prof1 = "'.$this->prof1.'", prof2 = "'.$this->prof2.'", prof1Skill = "'.$this->prof1Skill.'", prof2Skill = "'.$this->prof2Skill.'" WHERE charid ='.$this->charid.' AND uid ='.$this->uid);
			$this->mainCharProcessing();
		}
		$this->goHome();
	}
	
	public function __construct($db, $uid, $actionUid, $charName, $race, $class, $spec, $specLink, $level, $prof1, $prof2, $prof1Skill, $prof2Skill, $charid, $mainChar, $curChar){
		$this->db = $db;
		$this->uid = $uid;
		$this->actionUid = $actionUid;
		$this->charName = $charName;
		$this->race = $race;
		$this->class = $class;
		$this->spec = $spec;
		$this->specLink = $specLink;
		$this->level = $level;
		$this->prof1 = $prof1;
		$this->prof2 = $prof2;
		$this->prof1Skill = $prof1Skill;
		$this->prof2Skill = $prof2Skill;
		$this->charid = $charid;
		$this->mainChar = $mainChar;
		$this->curChar = $curChar;
		$this->commit();
	}
}
$keyData = new KeyData();
new UpdateChar(new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)), $_POST['uid'], $_POST['actionUid'], $_POST['charName'], $_POST['race'], $_POST['class'], $_POST['spec'], $_POST['specLink'], $_POST['level'], $_POST['prof1'], $_POST['prof2'], $_POST['prof1Skill'], $_POST['prof2Skill'], $_POST['charid'], $_POST['check'], $_POST['curChar']);
?>
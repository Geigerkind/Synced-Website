<?php
require_once '../Init.php';

class BSite extends Site{
	
	private function goHome(){
		if(!isset($_GET['uid']) OR $_GET['uid'] == null){
			header('Location: ../apply/');
			exit();
		}
	}
	
	private function returnStatus($cid){
		switch($cid){
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
			default :
				return 'Unknown';
				break;
		}
	}
	
	private function getNatioOptions($n){
		$handle = opendir ("../Database/img/flags");
		$content .= '<select name="natio" class="input-acc-dropdown input-dropdown border border-radius box-color">';
		while ($datei = readdir ($handle)) {
			$name = str_replace(".png", "", $datei);
			if($name != "." AND $name != ".."){
				if($n == $name){
					$content .= '<option selected>'.$name.'</option>"';
				}else{
					$content .= '<option>'.$name.'</option>"';
				}
			}
		}
		$content .= '<option>None</option>';
		$content .= '</select>';
		closedir($handle);
		return $content;
	}
	
	private function getRankOptions($rank, $ruid){
		if($this->userAgent->rank >= 5 AND $ruid != $this->userAgent->uid){
			$ranks = array('Inactive', 'Standby', 'Trialist', 'Member', 'Veteran', 'Classleader', 'Officer', 'Guild Master');
			$content .= '<select name="rank" class="input-acc-dropdown input-dropdown border border-radius box-color">';
				for($i = 0; $i <= sizeOf($ranks); $i++){
					if($i < $this->userAgent->rank){
						if($ranks[$i] == $rank)
							$content .= '<option selected>'.$ranks[$i].'</option>';
						else
							$content .= '<option>'.$ranks[$i].'</option>';
					}else{
						break;
					}
				}
			$content .= '<option>None</option>';
			$content .= '</select>';
		}else{
			$content .= $rank;
		}
		return $content;
	}
	
	private function getChars($selected){
		foreach($this->db->query('SELECT charName FROM user_char WHERE uid='.$_GET["uid"]) AS $c){
			if($c->charName == $selected)
				$chars .= '<option selected>'.$c->charName.'</option>';
			else
				$chars .= '<option>'.$c->charName.'</option>';
		}
		return $chars;
	}
	
	private function getRaces($selected){
		$races = array('Human', 'Dwarf', 'Gnome', 'Night Elf');
		foreach($races AS $val){
			if($val == $selected)
				$race .= '<option selected>'.$val.'</option>';
			else
				$race .= '<option>'.$val.'</option>';
		}
		return $race;
	}
	
	private function getClasses($selected){
		$classes = array('Warrior', 'Rogue', 'Priest', 'Hunter', 'Druid', 'Mage', 'Warlock', 'Paladin');
		foreach($classes AS $val){
			if($val == $selected)
				$class .= '<option selected>'.$val.'</option>';
			else
				$class .= '<option>'.$val.'</option>';
		}
		return $class;
	}
	
	private function getSpecs($selected, $class){
		foreach($this->db->query('SELECT * FROM class_spec WHERE class ="'.$class.'"') AS $val){
			if($val->name == $selected)
				$spec .= '<option selected>'.$val->name.'</option>';
			else
				$spec .= '<option>'.$val->name.'</option>';
		}
		$spec .= '<option>None</option>';
		return $spec;
	}
	
	private function getLevels($selected){
		for($i = 1; $i <= 60; $i++){
			if($i == $selected)
				$level .= '<option selected>'.$i.'</option>';
			else
				$level .= '<option>'.$i.'</option>';
		}
		return $level;
	}
	
	private function getProf($selected){
		$profs = array('Alchemy', 'Blacksmithing', 'Engineering', 'Leatherworking', 'Enchanting', 'Tailoring', 'Herbalism', 'Mining', 'Skinning', 'None');
		foreach($profs AS $val){
			if($val == $selected)
				$prof .= '<option selected>'.$val.'</option>';
			else
				$prof .= '<option>'.$val.'</option>';
		}
		$prof .= '<option>None</option>';
		return $prof;
	}
	
	private function getProfSkill($selected){
		$skills = array('Apprentice', 'Journeyman', 'Expert', 'Artisan');
		foreach($skills AS $val){
			if($val == $selected)
				$skill .= '<option selected>'.$val.'</option>';
			else
				$skill .= '<option>'.$val.'</option>';
		}
		$skill .= '<option>None</option>';
		return $skill;
	}
	
	private function leContent(){
		if(empty($_GET['char']))
			$row = $this->db->query('SELECT * FROM user a JOIN user_char b on a.uid = b.uid JOIN ranks c ON a.rank = c.rid WHERE b.mainchar = 1 AND a.uid ='.$_GET["uid"])->fetch();
		else
			$row = $this->db->query('SELECT * FROM user a JOIN user_char b on a.uid = b.uid JOIN ranks c ON a.rank = c.rid WHERE b.charName = "'.$_GET["char"].'" AND a.uid ='.$_GET["uid"])->fetch();
		$content .= '
			<div class="account-box border border-radius box-color">
				<div class="acc-left float-left box-color">
					<div class="acc-left-pic">
						<div class="acc-pic border border-radius box-color img-shadow centred-margin" style="background-image: url(\'{path}Database/img/User/'.$row->img.'.png\');"></div>
					</div>
					<div class="acc-left-pic-upload">
						<div class="acc-left-pic-upload-inside centred-margin">
							<form action="{path}Modules/account/imgUpload.php" method="post" enctype="multipart/form-data">
								<input type="text" value="'.$row->uid.'" name="uid" class="invisible" />
								<label class="upload_label box-color border border-radius">Search Image<input type="file" class="file_upload_input" name="uploadFile" /></label>
								<label class="upload_label_submit box-color border border-radius" title="Submit">Upload Image<input type="submit" class="file_upload_input_submit" name="submit" /></label>
							</form> 
						</div>
					</div>
		';
		if($this->userAgent->isOfficer() AND $this->userAgent->rank != $row->rank)
			$content .= '
					<div class="acc-left-confirm">
						<div class="acc-left-confirm-inside centred-margin">
							<form action="{path}Modules/account/evalState.php" method="post">
								<input type="hidden" value="'.$_GET["uid"].'" name="uid" />
								<input type="hidden" value="'.$this->userAgent->uid.'" name="actionUid" />
								<div class="button border-radius border box-color"><input type="submit" name="submit" value="1" class="invisible" id="confirm" /><a onclick="document.getElementById(\'confirm\').click()">Confirm</a></div>
								<div class="margin-top-5 button border-radius border box-color"><input type="submit" name="submit" value="0" class="invisible" id="on-hold" /><a onclick="document.getElementById(\'on-hold\').click()">On Hold</a></div>
								<div class="margin-top-5 button border-radius border box-color"><input type="submit" name="submit" value="2" class="invisible" id="decline" /><a onclick="document.getElementById(\'decline\').click()">Decline</a></div>
								<div class="margin-top-5 button border-radius border box-color"><input type="submit" name="submit" value="3" class="invisible" id="kick" /><a onclick="document.getElementById(\'kick\').click()">Kick</a></div>
							</form>
						</div>
					</div>
			';
		$content .= '
				</div>
				<div class="acc-right float-left border-left">
					<div class="acc-right-navbar border-bottom box-color">
						<div class="acc-navbar-button float-left text-bordered" onclick="util_ButtonShow(\'general\', \'character\')">General</div>
						<div class="acc-navbar-button float-left border-left text-bordered" onclick="util_ButtonShow(\'character\', \'general\')">Character</div>
		';
		if($this->userAgent->uid == $row->uid OR $this->userAgent->isOfficer() OR ($this->userAgent->isClassLeader() AND $this->userAgent->mainChar()->class == $row->class))
			$content .= '
						<div class="acc-navbar-edit float-left border-left" onclick="util_switch(\'general-edit,general-unedit,char-edit,char-unedit,acc-submit,acc-submit-char\')"></div>
			';
			$content .= '
					</div>
					<div id="general">
						<div id="general-unedit" class="acc-right-content">
							<div class="acc-right-row border-bottom text-bordered">
								<div class="acc-right-row-title border-bottom">Accountname:</div>
								<div class="acc-right-row-content">'.$row->name.'</div>
							</div>
							<div class="acc-right-row border-bottom text-bordered">
								<div class="acc-right-row-title border-bottom">Nationality:</div>
								<div class="acc-right-row-content">'.$row->natio.'</div>
							</div>
							<div class="acc-right-row border-bottom text-bordered">
								<div class="acc-right-row-title border-bottom">Rank:</div>
								<div class="acc-right-row-content">'.$row->rankname.'</div>
							</div>
							<div class="acc-right-row text-bordered">
								<div class="acc-right-row-title border-bottom">Status:</div>
								<div class="acc-right-row-content">'.$this->returnStatus($row->confirmed).'</div>
							</div>
						</div>
		';
		if($this->userAgent->uid == $row->uid OR $this->userAgent->isOfficer() OR ($this->userAgent->isClassLeader() AND $this->userAgent->mainChar()->class == $row->class))
			$content .= '
						<div id="general-edit" class="acc-right-content invisible">
							<form action="{path}Modules/account/updateGeneral.php" method="post">
								<div class="acc-right-row border-bottom text-bordered">
									<div class="acc-right-row-title border-bottom">Accountname:</div>
									<div class="acc-right-row-content"><input type="text" name="name" value="'.$row->name.'" class="input-acc input-text border border-radius box-color" /></div>
								</div>
								<div class="acc-right-row border-bottom text-bordered">
									<div class="acc-right-row-title border-bottom">Nationality:</div>
									<div class="acc-right-row-content">'.$this->getNatioOptions($row->natio).'</div>
								</div>
								<div class="acc-right-row border-bottom text-bordered">
									<div class="acc-right-row-title border-bottom">Rank:</div>
									<div class="acc-right-row-content">'.$this->getRankOptions($row->rankname, $row->uid).'</div>
								</div>
								<div class="acc-right-row text-bordered">
									<div class="acc-right-row-title border-bottom">Status:</div>
									<div class="acc-right-row-content">'.$this->returnStatus($row->confirmed).'</div>
								</div>
								<div id="acc-submit" class="acc-right-submit-general box-color border-top invisible">
									<input type="hidden" value="'.$row->uid.'" name="uid" />
									<input type="hidden" value="'.$this->userAgent->uid.'" name="actionUid" />
									<input type="submit" value="Submit" class="input-acc-submit input-submit border border-radius box-color" />
								</div>
							</form>
						</div>
			';
		if($row->mainChar == 1)
			$checked = 'checked';
		else
			$checkJs = '<script>$(".checkbox").prop("checked", false);</script>';
		$content .= '
					</div>
					<div id="character" class="invisible">
						<div id="char-unedit" class="acc-right-content">
							<div class="char-top border-bottom padding-10">
								<div class="char-top-pic img-shadow border box-color float-left" style="background-image: url(\'img/'.$row->race.'.jpg\');"></div>
								<div class="char-top-title text-bordered border-bottom float-left">Charactername:</div>
								<div class="char-top-content float-left">
									<div id="char-selector">
										<form action="{path}Modules/account/deleteChar.php" method="post">
											<select name="char" id="char-select" class="char-dropdown input-dropdown box-color border border-radius" onclick="accSelect(\''.$row->charName.'\', \''.$row->uid.'\')">
												'.$this->getChars($row->charName).'
												<option>Add an character</option>
											</select>
											<input type="hidden" name="uid" value="'.$row->uid.'" />
											<input type="hidden" name="auid" value="'.$this->userAgent->uid.'" />
											<input type="hidden" name="charid" value="'.$row->charid.'" />
											<input type="submit" value="Remove" class="char-remove input-submit border border-radius box-color">
											<div class="squaredThree">
												<input id="squaredThree" type="checkbox" class="checkbox" name="check" checked="'.$checked.'">
												<label for="squaredThree"></label>
												Main Character
											</div>
										</form>
									</div>
									<div id="char-selector-add" class="invisible">
										<form action="{path}Modules/account/createChar.php" method="post">
											<input type="hidden" value="'.$row->uid.'" name="uid" />
											<input type="hidden" value="'.$this->userAgent->uid.'" name="actionUid" />
											<input type="text" name="name" class="char-charName input-text border border-radius box-color" />
											<input type="submit" value="Add" class="char-add input-submit border border-radius box-color">
											<div class="button-select border border-radius box-color float-right" onclick="util_switch(\'char-selector-add,char-selector\')">Select</div>
											<div class="squaredThree">
												<input id="squaredThree" type="checkbox" name="check" value="None" '.$checked.'>
												<label for="squaredThree"></label>
												Main Character
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="char-bottom">
								<div class="acc-right-row border-bottom text-bordered">
									<div class="acc-right-row-sepa float-left">
										<div class="acc-right-row-sepa-title border-bottom">Race:</div>
										<div class="acc-right-row-sepa-content">'.$row->race.'</div>
									</div>
									<div class="acc-right-row-sepa float-left">
										<div class="acc-right-row-sepa-title border-bottom">Class:</div>
										<div class="acc-right-row-sepa-content">'.$row->class.'</div>
									</div>
								</div>
								<div class="acc-right-row border-bottom text-bordered">
									<div class="acc-right-row-sepa float-left">
										<div class="acc-right-row-sepa-title border-bottom">Spec:</div>
										<div class="acc-right-row-sepa-content">'.$row->spec.'</div>
									</div>
									<div class="acc-right-row-sepa float-left">
										<div class="acc-right-row-sepa-title border-bottom">Link:</div>
										<div class="acc-right-row-sepa-content"><a href="'.$row->specLink.'" target="_blank">'.$this->shortenstring(50, $row->specLink).'</a></div>
									</div>
								</div>
								<div class="acc-right-row border-bottom text-bordered">
									<div class="acc-right-row-sepa float-left">
										<div class="acc-right-row-sepa-title border-bottom">Level:</div>
										<div class="acc-right-row-sepa-content">'.$row->level.'</div>
									</div>
									<div class="acc-right-row-sepa float-left">
										<div class="acc-right-row-sepa-title border-bottom"></div>
										<div class="acc-right-row-sepa-content"></div>
									</div>
								</div>
								<div class="acc-right-row text-bordered">
									<div class="acc-right-row-sepa float-left">
										<div class="acc-right-row-sepa-title border-bottom">Profession:</div>
										<div class="acc-right-row-sepa-content">'.$row->prof1.' / '.$row->prof2.'</div>
									</div>
									<div class="acc-right-row-sepa float-left">
										<div class="acc-right-row-sepa-title border-bottom border-bottom">Skill:</div>
										<div class="acc-right-row-sepa-content">'.$row->prof1Skill.' / '.$row->prof2Skill.'</div>
									</div>
								</div>
							</div>
						</div>
						<div id="char-edit" class="acc-right-content invisible">
							<form action="{path}Modules/account/updateChar.php" method="post">
								<div class="char-top border-bottom padding-10">
									<div class="char-top-pic img-shadow border box-color float-left" style="background-image: url(\'img/'.$row->race.'.jpg\');"></div>
									<div class="char-top-title text-bordered border-bottom float-left">Charactername:</div>
									<div class="char-top-content float-left">
										<input type="text" class="char-charName input-text border border-radius box-color" value="'.$row->charName.'" name="charName" />
										<div class="squaredThree2">
											<input id="squaredThree2" type="checkbox" class="checkbox" name="check" checked="'.$checked.'">
											<label for="squaredThree2"></label>
											Main Character
										</div>
									</div>
								</div>
								<div class="char-bottom">
									<div class="acc-right-row border-bottom text-bordered">
										<div class="acc-right-row-sepa float-left">
											<div class="acc-right-row-sepa-title border-bottom">Race:</div>
											<div class="acc-right-row-sepa-content">
												<select name="race" class="char-dropdown input-dropdown border border-radius box-color">
													'.$this->getRaces($row->race).'
												</select>
											</div>
										</div>
										<div class="acc-right-row-sepa float-left">
											<div class="acc-right-row-sepa-title border-bottom">Class:</div>
											<div class="acc-right-row-sepa-content">
												<select name="class" class="char-dropdown input-dropdown border border-radius box-color">
													'.$this->getClasses($row->class).'
												</select>
											</div>
										</div>
									</div>
									<div class="acc-right-row border-bottom text-bordered">
										<div class="acc-right-row-sepa float-left">
											<div class="acc-right-row-sepa-title border-bottom">Spec:</div>
											<div class="acc-right-row-sepa-content">
												<select name="spec" class="char-dropdown input-dropdown border border-radius box-color">
													'.$this->getSpecs($row->spec, $row->class).'
												</select>
											</div>
										</div>
										<div class="acc-right-row-sepa float-left">
											<div class="acc-right-row-sepa-title border-bottom">Link:</div>
											<div class="acc-right-row-sepa-content">
												<input type="text" value="'.$row->specLink.'" class="char-text input-text border border-radius box-color" name="specLink" />
											</div>
										</div>
									</div>
									<div class="acc-right-row border-bottom text-bordered">
										<div class="acc-right-row-sepa float-left">
											<div class="acc-right-row-sepa-title border-bottom">Level:</div>
											<div class="acc-right-row-sepa-content">
												<select name="level" class="char-dropdown input-dropdown border border-radius box-color">
													'.$this->getLevels($row->level).'
												</select>
											</div>
										</div>
										<div class="acc-right-row-sepa float-left">
											<div class="acc-right-row-sepa-title border-bottom"></div>
											<div class="acc-right-row-sepa-content"></div>
										</div>
									</div>
									<div class="acc-right-row text-bordered">
										<div class="acc-right-row-sepa float-left">
											<div class="acc-right-row-sepa-title border-bottom">Profession:</div>
											<div class="acc-right-row-sepa-content">
												<select name="prof1" class="char-dropdown-prof input-dropdown border border-radius box-color">
													'.$this->getProf($row->prof1).'
												</select> /
												<select name="prof2" class="char-dropdown-prof input-dropdown border border-radius box-color">
													'.$this->getProf($row->prof2).'
												</select>
											</div>
										</div>
										<div class="acc-right-row-sepa float-left">
											<div class="acc-right-row-sepa-title border-bottom border-bottom">Skill:</div>
											<div class="acc-right-row-sepa-content">
												<select name="prof1Skill" class="char-dropdown-prof input-dropdown border border-radius box-color">
													'.$this->getProfSkill($row->prof1Skill).'
												</select> /
												<select name="prof2Skill" class="char-dropdown-prof input-dropdown border border-radius box-color">
													'.$this->getProfSkill($row->prof2Skill).'
												</select>
											</div>
										</div>
									</div>
									<div id="acc-submit-char" class="acc-right-submit-char box-color border-top invisible">
										<input type="hidden" value="'.$row->uid.'" name="uid" />
										<input type="hidden" value="'.$this->userAgent->uid.'" name="actionUid" />
										<input type="hidden" value="'.$row->charid.'" name="charid" />
										<input type="hidden" value="'.$_GET['char'].'" name="curChar" />
										<input type="submit" value="Submit" class="input-acc-submit input-submit border border-radius box-color" />
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			'.$checkJs.'
		';
		
		return $content;
	}
	
	public function buildContent(){
		pq('#bar-content')->replaceWith('
			<div id="bar-content" class="bar-center-main-content min-height padding-5">
				'.$this->leContent().'
			</div>
		');
	}
	
	function __construct($db, $theme = '', $userAgent, $parser, $file){
		parent::__construct($db, $theme, $userAgent, $parser, $file);
		$this->goHome();
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Account');
$site->run();
?>
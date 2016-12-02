<?php
require_once __DIR__ .'/../Init.php';

class BSite extends Site{
	private $class = array('Warrior', 'Rogue', 'Priest', 'Hunter', 'Druid', 'Mage', 'Warlock', 'Paladin');
	
	
	private function getMemberAmount(){
		return $this->db->query('SELECT uid FROM user WHERE confirmed = 1 AND rank>0')->rowCount();
	}
	
	private function getClassAmount($class){
		return $this->db->query('SELECT a.uid FROM user a JOIN user_char b ON a.uid = b.uid WHERE a.confirmed = 1 AND b.mainChar = 1 AND b.class = "'.$class.'"')->rowCount();
	}
	
	private function getUserInformation($class){
		return $this->db->query('SELECT * FROM user a JOIN user_char b ON a.uid = b.uid JOIN ranks c ON a.rank = c.rid WHERE a.confirmed = 1 AND b.mainChar = 1 AND b.class = "'.$class.'" AND a.rank>-1 ORDER BY a.rank DESC, a.name');
	}
	private function getUserCharInformation($uid){
		return $this->db->query('SELECT * FROM user_char WHERE uid ='.$uid);
	}
	
	private function leContent(){
		$content .= '
				<div class="roster-box min-height border border-radius box-color padding-10">
			';
		foreach($this->class AS $val){
			$content .= '
					<div class="roster-class-row border-bottom color-'.strtolower($val).'" style="background-image: url(\'img/member_'.strtolower($val).'.png\');">'.$val.' <span class="color-normal">('.$this->getClassAmount($val).')</span></div>
					<div class="roster-player-container padding-5 min-height box-color border border-bottom-radius">
			';
			foreach($this->getUserInformation($val) AS $row){
				$content .= '
						<div class="roster-player-row border-bottom box-color" onclick="util_switch(\'roster-'.$row->name.'\')">
							<div class="roster-player-row-natio float-left" title="'.$row->natio.'" style="background-image: url(\'{path}Database/img/flags/'.$row->natio.'.png\');"></div>
							<div class="roster-player-row-name float-left"><a href="{path}../account/?uid='.$row->uid.'" class="color-'.strtolower($val).'">'.$row->name.'</a></div>
							<div class="roster-player-row-rank float-left color-'.str_replace(" ", "", $row->rankname).'">'.$row->rankname.'</div>
							<div class="roster-player-row-jg float-left">'.$row->jgdate.'</div>
							<div class="roster-player-row-jg float-left">'.($r = ($this->userAgent->isOfficer()) ? gmdate("d.m.y H:i:s", $row->lastactive+7200) : '').'</div>
							<div class="roster-player-row-toggle float-left"></div>
						</div>
						<div id="roster-'.$row->name.'" class="roster-player-char-box border border-bottom-radius padding-5 min-height box-color invisible">
				';
				foreach($this->getUserCharInformation($row->uid) AS $char){
					$specLink = '#';
					if($char->specLink != 'None')
						$specLink = $char->specLink;
					$content .= '
							<div class="roster-player-char-row border-bottom">
								<div class="roster-player-char-row-level float-left">'.$char->level.'</div>
								<div class="roster-player-char-row-charname float-left"><a href="http://armory.twinstar.cz/character-sheet.xml?r=Kronos&cn='.$char->charName.'" target="_blank" class="color-'.strtolower($char->class).'">'.$char->charName.'</a></div>
								<div class="roster-player-char-row-race float-left" style="background-image: url(\'img/'.strtolower($char->race).rand(1,2).'.jpg\');">'.$char->race.'</div>
								<div class="roster-player-char-row-class float-left color-'.strtolower($char->class).'" style="background-image: url(\'img/member_'.strtolower($char->class).'.png\');">'.$char->class.'</div>
								<div class="roster-player-char-row-spec float-left" style="background-image: url(\'img/'.$char->class.'/'.strtolower($char->spec).'.jpg\');"><a href="'.$specLink.'" target="_blank" class="sy-yellow">'.$char->spec.'</a></div>
								<div class="roster-player-char-row-prof float-left" title="'.$char->prof1Skill.' '.$char->prof1.'" style="background-image: url(\'img/'.strtolower($char->prof1).'.jpg\');">'.$char->prof1.'</div>
								<div class="roster-player-char-row-prof float-left" title="'.$char->prof2Skill.' '.$char->prof2.'" style="background-image: url(\'img/'.strtolower($char->prof2).'.jpg\');">'.$char->prof2.'</div>
							</div>
					';
				}
				$content .= '
						</div>
				';
			}
			$content .= '
					</div>
			';
		}
		$content .= '
				</div>
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
		$this->setContentName('Roster ('.$this->getMemberAmount().')');
		if (!$userAgent->isConfirmed())
			header('Location: ../');
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->run();
?>
<?php
require_once '../Init.php';

class BSite extends Site{
	private $ts = 0;
	private $tes = 0;
	private $class = array();
	
	private function restoreLS(){
		$restore .= '<script type="text/javascript">';
		foreach($this->db->query('SELECT name FROM item_template') AS $item){
			$items .= '"'.$item->name.'",';
		}
		$items = rtrim($items, ",");
		$restore .= 'var items = ['.$items.']; $( ".autocom" ).autocomplete({source: items});';
		$restore .= 'restoreLS(\''.$_GET["class"].'\',\''.$_GET["player"].'\');</script>';
		return $restore;
	}
	
	private function leContent(){
		if($this->userAgent->isOfficer())
			$add = '<a href="add">(ADD)</a>';
		$content .= '
			<div id="history-system" class="ls-overall-box min-height box-color border border-radius">
				<div class="ls-overall-title box-color-no-opa padding-left-5 border-top-radius border-bottom text-bordered">History-System '.$add.'</div>
				<div class="ls-overall-content min-height padding-5">
		';
		foreach($this->class AS $value){
			$var = $this->db->query('SELECT ab.uid, ab.name, user_char.class, jgdate, (((SELECT COUNT(pid) FROM calender_event_participants JOIN calender_event ON calender_event_participants.eventid = calender_event.eventid WHERE calender_event_participants.uid = ab.uid AND role IN (1,2,3) AND timestamp BETWEEN '.$this->tes.' AND '.$this->ts.') + 0.5*(SELECT COUNT(pid) FROM calender_event_participants JOIN calender_event ON calender_event_participants.eventid = calender_event.eventid WHERE calender_event_participants.uid = ab.uid AND role = 4 AND timestamp BETWEEN '.$this->tes.' AND '.$this->ts.')) / (SELECT COUNT(eventid) FROM calender_event WHERE timestamp BETWEEN '.$this->tes.' AND '.$this->ts.')) AS ra30, (((SELECT COUNT(pid) FROM calender_event_participants JOIN calender_event ON calender_event_participants.eventid = calender_event.eventid WHERE calender_event_participants.uid = ab.uid AND role IN (1,2,3) AND timestamp <= '.$this->ts.') + 0.5*(SELECT COUNT(pid) FROM calender_event_participants JOIN calender_event ON calender_event_participants.eventid = calender_event.eventid WHERE calender_event_participants.uid = ab.uid AND role = 4 AND timestamp <= '.$this->ts.')) / (SELECT COUNT(eventid) FROM calender_event WHERE timestamp BETWEEN (SELECT timestamp FROM user z WHERE z.uid = ab.uid) AND '.$this->ts.' )) AS raaw FROM user ab JOIN user_char ON ab.uid = user_char.uid WHERE user_char.class = "'.$value.'" AND confirmed = 1 AND mainChar = 1 AND ab.rank != 0 ORDER BY ab.rank DESC, ab.name ASC');
			$content .= '
					<div class="ls-overall-class-seperator border-bottom" onclick="toggleUrl(\'ls-class-'.$value.'\', \'toClass\', \''.$value.'\')">
						<div class="ls-overall-class-title text-bold float-left color-'.strtolower($value).'" style="background-image: url(\'img/ls_'.strtolower($value).'.png\');">'.$value.' <span class="color-normal">('.$var->rowCount().')</span></div>
						<div class="ls-overall-class-toggle float-left" title="Toggle"></div>
					</div>
					<div id="ls-class-'.$value.'" class="ls-overall-class-container min-height padding-5 box-color border-bottom-radius border invisible">
			';
			foreach($var AS $rowPlayer){
				if($rowPlayer->ra30 == null) $rowPlayer->ra30 = 0;
				if($rowPlayer->raaw == null) $rowPlayer->raaw = 0;
				$content .= '
						<div class="ls-overall-player-row border-bottom" onclick="toggleUrl(\'ls-player-'.$rowPlayer->name.'\', \'toPlayer\', \''.$rowPlayer->name.'\')">
							<div class="ls-overall-player-column-name float-left color-'.strtolower($rowPlayer->class).'">'.$rowPlayer->name.'</div>
							<div class="ls-overall-player-column-jd float-left" title="Date of entry">'.$rowPlayer->jgdate.'</div>
							<div class="ls-overall-player-column-ra float-left" title="Raidattendance overall">'.($rowPlayer->raaw*100).' %</div>
							<div class="ls-overall-player-column-ra float-left" title="Raidattendance last 90 days">'.($rowPlayer->ra30*100).' %</div>
							<div class="ls-overall-player-column-toggle float-left" title="Toggle"></div>
						</div>
						<div id="ls-player-'.$rowPlayer->name.'" class="ls-overall-player-container border border-bottom-radius min-height invisible">
							<div class="ls-overall-player-container-item min-height float-left padding-5">
								<div class="ls-overall-player-container-item-title border-bottom text-bold">Item History:</div>
								<div class="ls-overall-player-container-item-content border-bottom padding-5">
				';
					foreach($this->db->query('SELECT a.id, b.name, a.uid, a.creatoruid, a.entry, a.date, c.name as creatorname FROM lc_item_history a JOIN item_template b ON a.entry = b.entry JOIN user c ON a.creatoruid = c.uid WHERE a.uid = '.$rowPlayer->uid.' ORDER BY a.id desc') AS $rowItem){
						$content .= '
									<div class="ls-overall-player-container-row border-bottom">
										<div class="ls-overall-player-container-row-item float-left" title="Added by '.$rowItem->creatorname.' on '.$rowItem->date.'"><a href="https://vanilla-twinhead.twinstar.cz/?item='.$rowItem->entry.'" rel="item='.$rowItem->entry.'">'.$rowItem->name.'</a></div>
						';
									if($this->userAgent->isOfficer()) $content .= '<div class="ls-overall-player-container-row-delete float-left" title="Delete"><form name="item-'.$rowItem->id.'" action="{path}Modules/loot-system/deleteItem.php" method="post"><input type="hidden" value="'.$this->userAgent->uid.'" name="uid" /><input type="hidden" value="'.$rowItem->id.'" name="id" /><input type="hidden" value="{location}" name="location" /></form><a href="#" onclick="submitOnClick(\'item-'.$rowItem->id.'\')"><img src="img/lc_delete.png" /></a></div>';
						$content .= '
									</div>
						';
					}
				$content .= '
								</div>
				';
					if($this->userAgent->isOfficer())
						$content .='
								<div class="ls-overall-player-container-item-form">
									<form action="{path}Modules/loot-system/addItem.php" method="post">
										<input type="hidden" value="'.$rowPlayer->uid.'" name="uid" />
										<input type="hidden" value="'.$this->userAgent->uid.'" name="cuid" />
										<input type="hidden" value="{location}" name="location" />
										<input type="text" class="autocom ls-item-text input-text border border-radius box-color" name="item" />
										<input type="submit" class="ls-item-submit input-submit border border-radius box-color" value="Submit" />
									</form>
								</div>
						';
						$content .= '
							</div>
						';
				if($this->userAgent->isOfficer() OR $this->userAgent->isClassLeader()){
					$content .= '
							<div class="ls-overall-player-container-note min-height float-left border-left padding-5">
								<div class="ls-overall-player-container-note-title border-bottom text-bold">Notes:</div>
								<div class="ls-overall-player-container-note-content border-bottom padding-5">
					';
					foreach($this->db->query('SELECT a.id, a.uid, a.text, a.date, a.creatoruid, b.name FROM lc_notes a JOIN user b ON a.creatoruid = b.uid WHERE a.uid = '.$rowPlayer->uid.' ORDER BY a.id desc') AS $rowNote){
						$content .= '
									<div class="ls-overall-player-container-row border-bottom">
										<div class="ls-overall-player-container-row-item float-left" title="Created by '.$rowNote->name.' on '.$rowNote->date.'">'.$rowNote->text.'</div>
										<div class="ls-overall-player-container-row-delete float-left" title="Delete"><form name="note-'.$rowNote->id.'" action="{path}Modules/loot-system/deleteNote.php" method="post"><input type="hidden" value="'.$this->userAgent->uid.'" name="uid" /><input type="hidden" value="'.$rowNote->id.'" name="id" /><input type="hidden" value="{location}" name="location" /></form><a href="#" onclick="submitOnClick(\'note-'.$rowNote->id.'\')"><img src="img/lc_delete.png" /></a></div>
									</div>
						';
					}
					$content .= '
								</div>
								<div class="ls-overall-player-container-note-form">
									<form action="{path}Modules/loot-system/addNote.php" method="post">
										<input type="hidden" value="'.$rowPlayer->uid.'" name="uid" />
										<input type="hidden" value="'.$this->userAgent->uid.'" name="cuid" />
										<input type="hidden" value="{location}" name="location" />
										<input type="text" class="ls-note-text input-text border border-radius box-color" name="note" />
										<input type="submit" class="ls-note-submit input-submit border border-radius box-color" value="Submit" />
									</form>
								</div>
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
			</div>
			<div class="ls-info-box min-height box-color border border-radius margin-top-5">
				<div class="ls-info-title box-color-no-opa padding-left-5 border-top-radius border-bottom text-bordered">General Information</div>
				<div class="ls-info-content min-height padding-5">
					The Loot Council is formed by the Officers and Classleaders, together they decide to which player a specific item should be given to considering a number of factors. <br />
					We always encourage our players to make agreements before the raid though! <br />
					Especially for set-loot (class specific loot), players should try to talk about it and make agreements. <br /><br />

					The Classleader is the moderator for classintern loot and he&lsquo;ll have the final word considering class specific items.<br /><br />

					As soon as an Item is posted in the raidchat, the players who want that item should write "x" to call for main-specc need, or "y" for off-specc need.<br />
					If it is a set-item, the Classleader will have the final word to decide who gets the item as stated above, but again making agreements among your classmates is always the best option!<br /><br />

					For non-set items it is more difficult to decide who should get the item. In these cases, the Loot Council comes into play.<br />
					Each Loot Council member will cast his vote in officer&lsquo;s chat for the player he sees as most deserving, by considering the following factors:

					<ul>
						<li>Raid Performance</li>
						<li>Raid Preparation (usage of consumables, enchanted gear, signing in for raid)</li>
						<li>Activity</li>
						<li>Recent Loothistory</li>
						<li>Guild Rank (Veterans and above > Members > Trialist)</li>
					</ul>

					If no clear decision can be made, the Loot Council will narrow it down to a few players who can roll for the item.<br /><br />

					Players who think they got treated unfairly, can contact an Officer to talk about the Loot Council&lsquo;s decision AFTER the raid, preferably in Teamspeak! Ragequitting the raid however, will lead to no improvement, it rather leads to a guild kick.<br />
					We are mature enough to talk in a respecting and friendly manner to each other, and only in that way you have a chance to get heard.
				</div>
			</div>
		';
		$content .= $this->restoreLS();
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
		$this->ts = time();
		$this->tes = $this->ts - 7776000;
		$this->class = array('Warrior', 'Rogue', 'Priest', 'Hunter', 'Druid', 'Mage', 'Warlock', 'Paladin');
		if (!$userAgent->isConfirmed())
			header('Location: ../');
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Loot-System');
$site->run();
?>
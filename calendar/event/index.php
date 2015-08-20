<?php
// UPDATE calender_event_participants c SET c.charid = (SELECT charid FROM user_char a JOIN USER b ON a.`uid` = b.`uid` WHERE a.`uid` = c.`charid` AND a.`mainChar` = 1)

require_once __DIR__ .'/../../Init.php';

class BSite extends Site{
	private $curTimestamp = 0;
	
	private function goHome(){
		if(!isset($_GET["date"]) OR empty($_GET["date"]) OR !$this->userAgent->isConfirmed()){
			header('Location: ../?date='.date('01.m.Y'));
			exit();
		}
	}
	
	private function goHomeRaw(){
		header('Location: ../?date='.date('j.m.Y'));
		exit();
	}
	
	private function getCurTimestamp(){
		$this->curTimestamp = strtotime($_GET["date"]);
	}
	
	private function availableContent(){
		if($this->db->query('SELECT eventid FROM calender_event WHERE date = "'.$_GET["date"].'"')->rowCount() != 0)
			return true;
		else
			return false;
	}
	
	private function getContent(){
		$row = $this->db->query('SELECT a.eventid, a.title, a.img, a.date, a.time, b.uid, b.name, a.description, a.timestamp FROM calender_event a JOIN user b ON a.creator = b.uid WHERE a.date = "'.$_GET["date"].'"')->fetch();
		$q1 = $this->db->query('SELECT * FROM calender_event_participants a JOIN user_char b ON a.charid = b.charid JOIN user c ON b.uid = c.uid WHERE a.role = 3 AND a.eventid ='.$row->eventid);
		$q2 = $this->db->query('SELECT * FROM calender_event_participants a JOIN user_char b ON a.charid = b.charid JOIN user c ON b.uid = c.uid WHERE a.role = 1 AND a.eventid ='.$row->eventid);
		$q3 = $this->db->query('SELECT * FROM calender_event_participants a JOIN user_char b ON a.charid = b.charid JOIN user c ON b.uid = c.uid WHERE a.role = 2 AND a.eventid ='.$row->eventid);
		if ($this->userAgent->isClassLeader())
			$officeredit = '<span class="float-right"><a href="{path}calendar/event/cedit/index.php?eventid='.$row->eventid.'&date='.$_GET["date"].'">edit </a><a href="{path}Modules/calendar/deleteEvent.php?eventid='.$row->eventid.'"><img src="{path}calendar/event/img/trashcan.png" /></a></span>';
		$content .= '
			<div class="event-content-box border border-radius box-color min-height padding-10">
				<div class="event-content-top">
					<div class="event-content-pic border img-shadow box-color float-left" style="background-image: url(\'img/r'.$row->img.'.png\');"></div>
					<div class="event-content-title float-left border-bottom">'.$row->title.$officeredit.'</div>
					<div class="event-content-descr float-left">
						<div class="event-content-descr-row">Date: '.$row->date.'</div>
						<div class="event-content-descr-row">Time: '.$row->time.'</div>
						<div class="event-content-descr-row">Creator: <a href="{host}/account/?uid='.$row->uid.'" class="sy-yellow">'.$row->name.'</a></div>
						<div class="event-content-descr-row">Description: '.$this->parser->parse($row->description)->getAsHtml().'</div>
					</div>
				</div>
				<div class="event-content-center min-height">
					<div class="event-content-center-navbar">
						<ul>
							<li class="border float-left box-color" onclick="hackEventButton(\'roles\')">Roles</li>
							<li class="border float-left box-color margin-left-5" onclick="hackEventButton(\'classes\')">Classes</li>
							<li class="border float-left box-color margin-left-5" onclick="hackEventButton(\'signedOut\')">Signed Out</li>
							<li class="border float-left box-color margin-left-5" onclick="hackEventButton(\'notintime\')">Not in time</li>
							<li class="border float-left box-color margin-left-5" onclick="hackEventButton(\'notsignedinout\')">Not signed in/out</li>
						</ul>
					</div>
					<div id="roles" class="event-content-center-container min-height">
						<div id="tank" class="player-role-sep min-height float-left">
							<div class="player-role-sep-title border box-color">Tank ('.$q1->rowCount().')</div>
							<div class="player-role-sep-content">
		';
		foreach($q1 AS $val){
			$note = '';
			if(!empty($val->note))
				$note = '*';
			else
				$val->note = '[NO NOTE]';
			if ($this->userAgent->isClassLeader())
				$officerrowedit = 'onclick="PopUserHandler('.$val->uid.','.$row->eventid.', \''.$row->date.'\')"';
			$content .= '
								<div class="player-role-row color-'.strtolower($val->class).'" style="background-image: url(\'img/member_'.strtolower($val->class).'.png\');" title="Submited on '.$val->date.'&#13;Note: '.$val->note.'" '.$officerrowedit.'>'.$val->name.$note.'</div>
			';
		}
		$content .= '
							</div>
						</div>
						<div id="dps" class="player-role-sep min-height float-left">
							<div class="player-role-sep-title border box-color">DPS ('.$q2->rowCount().')</div>
							<div class="player-role-sep-content">
		';
		foreach($q2 AS $val){
			$note = '';
			if(!empty($val->note))
				$note = '*';
			else
				$val->note = '[NO NOTE]';
			if ($this->userAgent->isClassLeader())
				$officerrowedit = 'onclick="PopUserHandler('.$val->uid.','.$row->eventid.', \''.$row->date.'\')"';
			$content .= '
								<div class="player-role-row color-'.strtolower($val->class).'" style="background-image: url(\'img/member_'.strtolower($val->class).'.png\');" title="Submited on '.$val->date.'&#13;Note: '.$val->note.'" '.$officerrowedit.'>'.$val->name.$note.'</div>
			';
		}
		$content .= '
							</div>
						</div>
						<div id="heal" class="player-role-sep min-height float-left">
							<div class="player-role-sep-title border box-color">Heal ('.$q3->rowCount().')</div>
							<div class="player-role-sep-content">
		';
		foreach($q3 AS $val){
			$note = '';
			if(!empty($val->note))
				$note = '*';
			else
				$val->note = '[NO NOTE]';
			if ($this->userAgent->isClassLeader())
				$officerrowedit = 'onclick="PopUserHandler('.$val->uid.','.$row->eventid.', \''.$row->date.'\')"';
			$content .= '
								<div class="player-role-row color-'.strtolower($val->class).'" style="background-image: url(\'img/member_'.strtolower($val->class).'.png\');" title="Submited on '.$val->date.'&#13;Note: '.$val->note.'" '.$officerrowedit.'>'.$val->name.$note.'</div>
			';
		}
		$content .= '
							</div>
						</div>
					</div>
					<div id="classes" class="event-content-center-container invisible">
						<div class="player-class-sep min-height float-left">
		';
		foreach(array('Warrior', 'Rogue', 'Priest') AS $val){
			$k = $this->db->query('SELECT * FROM calender_event_participants a JOIN user_char b ON a.charid = b.charid JOIN user c ON b.uid = c.uid WHERE a.role != 4 AND a.eventid = '.$row->eventid.'  AND b.class = "'.$val.'"');
			$content .= '
							<div id="e-'.$val.'" class="player-role-sep min-height">
								<div class="player-role-sep-title border box-color color-'.strtolower($val).'">'.$val.' ('.$k->rowCount().')</div>
								<div class="player-role-sep-content">
			';
			foreach($k AS $o){
				$note = '';
				if(!empty($o->note))
					$note = '*';
				else
					$o->note = '[NO NOTE]';
				if ($this->userAgent->isClassLeader())
					$officerrowedit = 'onclick="PopUserHandler('.$o->uid.','.$row->eventid.', \''.$row->date.'\')"';
				$content .= '
									<div class="player-role-row color-'.strtolower($o->class).'" style="background-image: url(\'img/member_'.strtolower($o->class).'.png\');" title="Submited on '.$o->date.'&#13;Note: '.$o->note.'" '.$officerrowedit.'>'.$o->name.$note.'</div>
				';
			}
			$content .= '
								</div>
							</div>
			';
		}
		$content .= '
						</div>
						<div class="player-class-sep min-height float-left">
		';
		foreach(array('Hunter', 'Druid') AS $val){
			$k = $this->db->query('SELECT * FROM calender_event_participants a JOIN user_char b ON a.charid = b.charid JOIN user c ON b.uid = c.uid WHERE a.role != 4 AND a.eventid = '.$row->eventid.'  AND b.class = "'.$val.'"');
			$content .= '
							<div id="e-'.$val.'" class="player-role-sep min-height">
								<div class="player-role-sep-title border box-color color-'.strtolower($val).'">'.$val.' ('.$k->rowCount().')</div>
								<div class="player-role-sep-content">
			';
			foreach($k AS $o){
				$note = '';
				if(!empty($o->note))
					$note = '*';
				else
					$o->note = '[NO NOTE]';
				if ($this->userAgent->isClassLeader())
					$officerrowedit = 'onclick="PopUserHandler('.$o->uid.','.$row->eventid.', \''.$row->date.'\')"';
				$content .= '
									<div class="player-role-row color-'.strtolower($o->class).'" style="background-image: url(\'img/member_'.strtolower($o->class).'.png\');" title="Submited on '.$o->date.'&#13;Note: '.$o->note.'" '.$officerrowedit.'>'.$o->name.$note.'</div>
				';
			}
			$content .= '
								</div>
							</div>
			';
		}
		$content .= '
						</div>
						<div class="player-class-sep min-height float-left">
		';
		foreach(array('Mage', 'Warlock', 'Paladin') AS $val){
			$k = $this->db->query('SELECT * FROM calender_event_participants a JOIN user_char b ON a.charid = b.charid JOIN user c ON b.uid = c.uid WHERE a.role != 4 AND a.eventid = '.$row->eventid.' AND b.class = "'.$val.'"');
			$content .= '
							<div id="e-'.$val.'" class="player-role-sep min-height">
								<div class="player-role-sep-title border box-color color-'.strtolower($val).'">'.$val.' ('.$k->rowCount().')</div>
								<div class="player-role-sep-content">
			';
			foreach($k AS $o){
				$note = '';
				if(!empty($o->note))
					$note = '*';
				else
					$o->note = '[NO NOTE]';
				if ($this->userAgent->isClassLeader())
					$officerrowedit = 'onclick="PopUserHandler('.$o->uid.','.$row->eventid.', \''.$row->date.'\')"';
				$content .= '
									<div class="player-role-row color-'.strtolower($o->class).'" style="background-image: url(\'img/member_'.strtolower($o->class).'.png\');" title="Submited on '.$o->date.'&#13;Note: '.$o->note.'" '.$officerrowedit.'>'.$o->name.$note.'</div>
				';
			}
			$content .= '
								</div>
							</div>
			';
		}
		$z = $this->db->query('SELECT * FROM calender_event_participants a JOIN user_char b ON a.charid = b.charid JOIN user c ON b.uid = c.uid WHERE a.role = 4 AND a.eventid = '.$row->eventid);
		$content .= '
						</div>
					</div>
					<div id="signedOut" class="event-content-center-container invisible">
						<div class="signedOut-box min-height centred-margin">
							<div class="signedOut-title border box-color">Signed Out ('.$z->rowCount().')</div>
							<div class="signedOut-content min-height">
		';
		foreach($z AS $u){
			$note = '';
			if(!empty($u->note))
				$note = '*';
			else
				$u->note = '[NO NOTE]';
			if ($this->userAgent->isClassLeader())
				$officerrowedit = 'onclick="PopUserHandler('.$u->uid.','.$row->eventid.', \''.$row->date.'\')"';
			$content .= '
								<div class="signedOut-row float-left color-'.strtolower($u->class).'" style="background-image: url(\'img/member_'.strtolower($u->class).'.png\');" title="Submited on '.$u->date.'&#13;Note: '.$u->note.'" '.$officerrowedit.'>'.$u->name.$note.'</div>
			';
		}
		$z2 = $this->db->query('SELECT * FROM calender_event_participants a JOIN user_char b ON a.charid = b.charid JOIN user c ON b.uid = c.uid WHERE a.role = 5 AND a.eventid = '.$row->eventid);
		$content .= '
							</div>
						</div>
					</div>
					<div id="notintime" class="event-content-center-container invisible">
						<div class="signedOut-box min-height centred-margin">
							<div class="signedOut-title border box-color">Were too lazy too sign in in time ('.$z2->rowCount().')</div>
							<div class="signedOut-content min-height">
		';
		foreach($z2 AS $u){
			$note = '';
			if(!empty($u->note))
				$note = '*';
			else
				$u->note = '[NO NOTE]';
			if ($this->userAgent->isClassLeader())
				$officerrowedit = 'onclick="PopUserHandler('.$u->uid.','.$row->eventid.', \''.$row->date.'\')"';
			$content .= '
								<div class="signedOut-row float-left color-'.strtolower($u->class).'" style="background-image: url(\'img/member_'.strtolower($u->class).'.png\');" title="Submited on '.$u->date.'&#13;Note: '.$u->note.'" '.$officerrowedit.'>'.$u->name.$note.'</div>
			';
		}
		$z3 = $this->db->query('SELECT * FROM user a JOIN user_char b ON a.uid = b.uid WHERE b.mainChar = 1 AND a.confirmed = 1 AND a.rank > 0 AND a.uid NOT IN (SELECT uid FROM calender_event_participants WHERE eventid='.$row->eventid.')');
		$content .= '
							</div>
						</div>
					</div>
					<div id="notsignedinout" class="event-content-center-container invisible">
						<div class="signedOut-box min-height centred-margin">
							<div class="signedOut-title border box-color">Not signed in or out yet ('.$z3->rowCount().')</div>
							<div class="signedOut-content min-height">
		';
		foreach($z3 AS $u){
			$note = '';
			if(!empty($u->note))
				$note = '*';
			else
				$u->note = '[NO NOTE]';
			if ($this->userAgent->isClassLeader())
				$officerrowedit = 'onclick="PopUserHandler('.$u->uid.','.$row->eventid.', \''.$row->date.'\')"';
			$content .= '
								<div class="signedOut-row float-left color-'.strtolower($u->class).'" style="background-image: url(\'img/member_'.strtolower($u->class).'.png\');" title="Submited on '.$u->date.'&#13;Note: '.$u->note.'" '.$officerrowedit.'>'.$u->name.$note.'</div>
			';
		}
		$content .= '
							</div>
						</div>
					</div>
				</div>
		';
		if ($this->userAgent->isClassLeader()){
			$content .= '
				<div id="u-handler" class="user-handler invisible">
					<div class="user-handler-row"><a href="" id="u-link1">Remove Player</a></div>
					<div class="user-handler-row"><a href="" id="u-link2">Move to Not in Time</a></div>
				</div>
			';
		}

		if($this->userAgent->isConfirmed() AND (time() - 60*60*24) <= $row->timestamp){
			$content .= '
				<div class="event-content-bottom">
					<form action="{path}Modules/calendar/participateEvent.php" method="post">
						<div class="event-bottom-row-container float-left">
							<div class="event-content-bottom-row">
								<div class="event-content-bottom-row-sep float-left">
									<label>Note:</label>
								</div>
								<div class="event-content-bottom-row-sep float-left">
									<label>Character:</label>
								</div>
								<div class="event-content-bottom-row-sep float-left">
									<label>Role:</label>
								</div>
							</div>
							<div class="event-content-bottom-row">
								<div class="event-content-bottom-row-sep float-left">
									<input type="text" name="note" value="'.$this->db->query('SELECT note FROM calender_event_participants WHERE eventid ='.$row->eventid.' AND uid ='.$this->userAgent->uid)->fetch()->note.'" class="event-note input-text border border-radius box-color" />
								</div>
								<div class="event-content-bottom-row-sep float-left">
									<select name="charName" class="event-select input-dropdown box-color border border-radius">
		';
		foreach($this->db->query('SELECT charName FROM user_char WHERE uid ='.$this->userAgent->uid) AS $character){
			if($character->charName == $this->userAgent->mainChar()->charName){
				$content .= '
										<option selected>'.$character->charName.'</option>
				';
			}else{
				$content .= '
										<option>'.$character->charName.'</option>
				';
			}
			
		}
		$content .= '
									</select>
								</div>
								<div class="event-content-bottom-row-sep float-left">
									<select name="role" class="event-select input-dropdown box-color border border-radius">
		';
		if (time() + 60*60*5 <= $row->timestamp){
			$content .= '
										<option>DPS</option>
										<option>Tank</option>
										<option>Heal</option>
										<option>Sign Out</option>
			';
		}else{
			$content .= '
										<option>Forgot to sign in</option>
			';
		}
		$content .= '
		
									</select>
								</div>
							</div>
						</div>
						<div class="event-content-bottom-row-large float-left">
							<input type="hidden" value="'.$this->userAgent->uid.'" name="uid" />
							<input type="hidden" value="'.$row->eventid.'" name="eventid" />
							<input type="submit" value="Submit" class="event-submit input-submit border border-radius box-color" />
						</div>
					</form>
				</div>
		';
		}
		$content .= '
			</div>
		';
		return $content;
	}
	
	private function getForm(){
		$content .= '
			<div class="event-form-box min-height border border-radius box-color padding-10">
				<form action="{path}Modules/calendar/createEvent.php" method="post">
					<div class="event-form-tile min-height border-bottom">
						<div class="event-form-tile-row">
							<label>Title of the event:</label>
						</div>
						<div class="event-form-tile-row">
							<input type="text" name="title" class="event-form-text input-text border border-radius box-color" />
						</div>
					</div>
					<div class="event-form-tile min-height border-bottom">
						<div class="event-form-tile-sep float-left">
							<div class="event-form-tile-sep-row">
								<label>Image:</label>
							</div>
							<div class="event-form-tile-sep-row">
								<select name="img" class="event-form-tile-input input-dropdown border border-radius box-color">
									<option>Default</option>
									<option>Molten Core</option>
									<option>Black Wing Lair</option>
									<option>Onyxia\'s Lair</option>
								</select>
							</div>
						</div>
						<div class="event-form-tile-sep float-left">
							<div class="event-form-tile-sep-row">
								<label>Time:</label>
							</div>
							<div class="event-form-tile-sep-row">
								<input type="text" name="time" class="event-form-tile-input-text input-text border border-radius box-color" />
							</div>
						</div>
					</div>
					<div class="event-form-tile min-height border-bottom">
						<div class="event-form-tile-sep-row">
							<label>Description:</label>
						</div>
						<div class="thread-bbcode-box">
							<div class="thread-bbcode-box-button text-bold border-radius float-left" title="Bold text: [b]text[/b]" onclick="textarea_bbcode(\'b\')">B</div> 
							<div class="thread-bbcode-box-button text-italic border-radius float-left" title="Italic text: [i]text[/i]" onclick="textarea_bbcode(\'i\')">i</div>
							<div class="thread-bbcode-box-button text-underline border-radius float-left"  title="Underline text: [u]text[/u]" onclick="textarea_bbcode(\'u\')">u</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="Quote text: [quote]text[/quote]" onclick="textarea_bbcode(\'quote\')">Quote</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="Code display: [code]text[/code]" onclick="textarea_bbcode(\'code\')">Code</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="List: [ul][li]item[/li][/ul]" onclick="textarea_bbcode(\'ul\')">List</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="List item: [li]text[/li]" onclick="textarea_bbcode(\'li\')">[*]</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="Insert image: [img]http://image_url.com[/img]" onclick="textarea_bbcode(\'img\')">Img</div>
							<div class="thread-bbcode-box-button text-underline border-radius float-left" title="Insert URL: [url=http://url.com]text[/url] or [url]http://url.com[/url]" onclick="textarea_bbcode(\'url\')">URL</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="Huge text: [h1]text[/h1]" onclick="textarea_bbcode(\'h1\')">H1</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="Large text: [h2]text[/h2]" onclick="textarea_bbcode(\'h2\')">H2</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="Normal text: [h3]text[/h3]" onclick="textarea_bbcode(\'h3\')">H3</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="Tiny text: [h4]text[/h4]" onclick="textarea_bbcode(\'h4\')">H4</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="Select colour: [color=red]text[/color] Tip: You can also use hex code" onclick="textarea_bbcode(\'color\')">Color</div>
							<div class="thread-bbcode-box-button border-radius float-left" title="Add Item: [item]itemid[/item]" onclick="textarea_bbcode(\'item\')">Item</div>
						</div>
						<textarea class="thread-textarea" id="textedit" name="text"></textarea>
					</div>
					<div class="event-form-tile min-height">
						<input type="hidden" value="'.$this->userAgent->uid.'" name="uid" />
						<input type="hidden" value="'.$_GET["date"].'" name="date" />
						<input type="submit" value="Submit" class="event-char-submit input-submit border border-radius box-color" />
					</div>
				</form>
			</div>
		';
		return $content;
	}
	
	private function leContent(){
		if($this->availableContent()){
			$content .= $this->getContent();
		}else{
			if($this->userAgent->isConfirmed() and $this->userAgent->isClassLeader())
				$content .= $this->getForm();
			else
				$this->goHomeRaw();
		}
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
		$this->getCurTimestamp();
		$this->setContentName('Calendar - '.$_GET["date"]);
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->run();
?>
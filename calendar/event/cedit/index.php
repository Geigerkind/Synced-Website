<?php
// UPDATE calender_event_participants c SET c.charid = (SELECT charid FROM user_char a JOIN USER b ON a.`uid` = b.`uid` WHERE a.`uid` = c.`charid` AND a.`mainChar` = 1)

require_once __DIR__ .'/../../../Init.php';

class BSite extends Site{
	private $curTimestamp = 0;
	
	private function goHome(){
		if(!$this->userAgent->isClassLeader()){
			header('Location: ../?date='.date('01.m.Y'));
			exit();
		}
	}
	
	private function leContent(){
		$row = $this->db->query('SELECT * FROM calender_event WHERE eventid='.$_GET["eventid"])->fetch();
		$content .= '
			<div class="event-form-box min-height border border-radius box-color padding-10">
				<form action="{path}Modules/calendar/editEvent.php" method="post">
					<div class="event-form-tile min-height border-bottom">
						<div class="event-form-tile-row">
							<label>Title of the event:</label>
						</div>
						<div class="event-form-tile-row">
							<input type="text" name="title" class="event-form-text input-text border border-radius box-color" value="'.$row->title.'" />
						</div>
					</div>
					<div class="event-form-tile min-height border-bottom">
						<div class="event-form-tile-sep float-left">
							<div class="event-form-tile-sep-row">
								<label>Image:</label>
							</div>
							<div class="event-form-tile-sep-row">
								<select name="img" class="event-form-tile-input input-dropdown border border-radius box-color">
									<option '; if($row->img == 0){$content .= 'selected';} $content .='>Default</option>
									<option '; if($row->img == 1){$content .= 'selected';} $content .='>Molten Core</option>
									<option '; if($row->img == 2){$content .= 'selected';} $content .='>Black Wing Lair</option>
									<option '; if($row->img == 3){$content .= 'selected';} $content .='>Onyxia\'s Lair</option>
									<option '; if($row->img == 4){$content .= 'selected';} $content .='>Zul\'Gurub</option>
								</select>
							</div>
						</div>
						<div class="event-form-tile-sep float-left">
							<div class="event-form-tile-sep-row">
								<label>Time:</label>
							</div>
							<div class="event-form-tile-sep-row">
								<input type="text" name="time" class="event-form-tile-input-text input-text border border-radius box-color" value="'.$row->time.'"/>
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
						<textarea class="thread-textarea" id="textedit" name="text">'.$row->description.'</textarea>
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
		$this->setContentName('Edit calendar - '.$_GET["date"]);
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->run();
?>
<?php
require_once __DIR__ .'/../../Init.php';

class BSite extends Site{
	
	private function goHome(){
		if(!isset($_GET["id"]) OR empty($_GET["id"]) or !$this->userAgent->isConfirmed()){
			header('Location: ../../');
			exit();
		}
	}
	
	private function getContent($type, $link){
		switch($type){
			case 1 :
				return '<img src="{path}Database/img/media/'.$link.'_large.png" width="596" height="390" />';
				break;
			case 2 :
				return '<iframe width="596" height="390" src="https://www.youtube.com/embed/'.$link.'" frameborder="0" allowfullscreen></iframe>';
				break;
			case 3 :
				return '<iframe src="http://www.twitch.tv/'.$link.'/embed" frameborder="0" scrolling="no" height="390" width="596"></iframe>';
				break;
		}
	}
	
	private function getObject($id){
		switch($this->db->query('SELECT type FROM media_media WHERE vmid ='.$id)->fetch()->type){
			case 1 :
				return $this->db->query('SELECT * FROM media_media a JOIN media_screenshot b ON a.id = b.id JOIN user c ON b.user = c.uid WHERE vmid ='.$id);
				break;
			case 2 :
				return $this->db->query('SELECT * FROM media_media a JOIN media_video b ON a.id = b.id JOIN user c ON b.user = c.uid WHERE vmid ='.$id);
				break;
			case 3 :
				return $this->db->query('SELECT * FROM media_media a JOIN media_stream b ON a.id = b.id JOIN user c ON b.user = c.uid WHERE vmid ='.$id);
				break;
		}
	}
	
	private function getLeftArrow(){
		$q = $this->db->query('SELECT vmid FROM media_media WHERE vmid < '.$_GET["id"].' ORDER BY vmid DESC');
		if($q->rowCount() != 0){
			return '<a href="{host}/media/show/?id='.$q->fetch()->vmid.'"><div class="arrow" style="background-image: url(\'img/arrow-left.png\');"></div></a>';	
		}
	}
	
	private function getRightArrow(){
		$q = $this->db->query('SELECT vmid FROM media_media WHERE vmid > '.$_GET["id"].' ORDER BY vmid DESC');
		if($q->rowCount() != 0){
			return '<a href="{host}/media/show/?id='.$q->fetch()->vmid.'"><div class="arrow" style="background-image: url(\'img/arrow-right.png\');"></div></a>';	
		}
	}
	
	private function getEdit($type){
		switch($type){
			case 3 :
				return 'editStream.php';
				break;
			case 2 :
				return 'editVideo.php';
				break;
			case 1 :
				return 'editScreenshot.php';
				break;
		}
	}
	
	private function getUserPanel($uid, $vmid){
		if($this->userAgent->uid == $uid OR $this->userAgent->isOfficer())
			return '<form name="delete" action="{path}Modules/media/deleteMedia.php" method="post"><input type="hidden" value="'.$this->userAgent->uid.'" name="actionUid" /><input type="hidden" value="'.$vmid.'" name="vmid" /></form><a onclick="submitOnClick(\'delete\')">[Delete]</a> | <a onclick="toggle(\'edit\')">[Edit]</a>';
	}
	
	private function leContent(){
		$row = $this->getObject($_GET["id"])->fetch();
		$comments = $this->db->query('SELECT * FROM media_comments a JOIN user b ON a.uid = b.uid JOIN user_char c ON b.uid = c.uid WHERE c.mainChar = 1 AND a.vmid ='.$row->vmid.' ORDER BY a.mcid DESC');
		
		$content .= '
			<div class="show-box border border-radius box-color padding-10">
				<div class="show-box-media">
					<div class="show-box-media-arrow float-left">
						'.$this->getLeftArrow().'
					</div>
					<a href="{path}Database/img/media/'.$row->link.'_large.png" rel="lightbox">
						<div class="show-box-media-media border img-shadow box-color float-left">'.$this->getContent($row->type, $row->link).'</div>
					</a>
					<div class="show-box-media-arrow float-left">
						'.$this->getRightArrow().'
					</div>
				</div>
				<div class="show-box-descr">
					<div class="show-descr-title">'.$row->title.'</div>
					<div class="show-descr-subtitle">
						<div class="show-descr-subtitle-left float-left">Uploaded by '.$row->name.' on '.htmlentities($row->date).'</div>
						<div class="show-descr-subtitle-right float-left">'.$this->getUserPanel($row->uid, $row->vmid).'</div>
					</div>
					<div class="show-descr">'.$row->descr.'</div>
					<div id="edit" class="show-descr-edit padding-10 invisible">
						<form action="{path}Modules/media/'.$this->getEdit($row->type).'" method="post">
							<div class="show-edit-row border-bottom">
								<div class="show-edit-row-title float-left">Title:</div>
								<div class="show-edit-row-content float-left"><input type="text" name="title" class="input-text border border-radius box-color" value="'.$row->title.'" /></div>
							</div>
							<div class="show-edit-row border-bottom">
								<div class="show-edit-row-title float-left">Description:</div>
								<div class="show-edit-row-content float-left"><input type="text" name="text" class="input-text border border-radius box-color" value="'.$row->descr.'" /></div>
							</div>
							<div class="show-edit-row text-center">
								<input type="hidden" value="'.$row->uid.'" name="uid" />
								<input type="hidden" value="'.$this->userAgent->uid.'" name="actionUid" />
								<input type="hidden" value="'.$row->vmid.'" name="vmid" />
								<input type="submit" value="Submit" id="edit-submit" class="input-submit border border-radius box-color" />
							</div>
						</form>
					</div>
				</div>
				<div class="show-box-comments min-height">
					<div class="show-box-comments-title">Comments ('.$comments->rowCount().')</div>
					<div class="show-box-comments-content min-height">
		';
		foreach($comments AS $c){
			$content .= '
						<div class="show-comments-item box-color border-radius padding-10">
							<div class="show-comments-item-pic border img-shadow box-color float-left" style="background-image: url(\'{path}Database/img/User/'.$c->img.'.png\');"></div>
							<div class="show-comments-item-title float-left"><span class="color-'.strtolower($c->class).'">'.$c->name.'</span> '.$c->date.'</div>
							<div class="show-comments-item-content float-left">'.$c->text.'</div>
						</div>
			';
		}
		$content .= '
					</div>
				</div>
		';
		if($this->userAgent->uid == $row->uid OR $this->userAgent->isOfficer())
			$content .= '
				<div class="show-box-form">
					<form action="{path}Modules/media/createComment.php" method="post">
						<div class="thread-bbcode-box media-bbcode-box">
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
						<textarea class="thread-textarea media-textarea" id="textedit" name="text"></textarea>
						<input type="hidden" value="'.$this->userAgent->uid.'" name="uid" />
						<input type="hidden" value="'.$row->vmid.'" name="vmid" />
						<input type="submit" value="Submit" id="comment-submit" class="input-submit border border-radius box-color" />
					</form>
				</div
			';
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
		$this->goHome();
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Media');
$site->run();
?>
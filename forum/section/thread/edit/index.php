<?php
require_once __DIR__ .'/../../../../Init.php';

class BSite extends Site{
	
	private function goHome($uid){
		if((!isset($_GET['cid']) OR empty($_GET['cid']) OR $this->userAgent->uid != $uid) AND !$this->userAgent->isClassLeader()){
			header('Location: ../../../');
			exit();
		}
	}
	
	private function formatText($text = ''){
		return str_replace(array('<br>', '<br />'), '', $text);
	}
	
	private function leForm(){
		$edit = $this->db->query('SELECT * FROM forum_topics_comment WHERE cid ='.$_GET["cid"])->fetch();
		$this->goHome($edit->uid);
		$form .= '
			<div class="edit-box border border-radius box-color min-height padding-5">
				<form action="{path}Modules/thread/editThread.php" method="post">
					<div class="edit-top border-bottom">
						<label>Title of the thread:</label>
						<input type="text" class="input-text border border-radius box-color edit-title" name="title" value="'.$edit->title.'" />
					</div>
					<div class="edit-center border-bottom">
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
						<textarea name="text" class="thread-textarea" id="textedit">'.$this->formatText($edit->text).'</textarea>
					</div>
					<div class="edit-bottom min-height padding-5">
						<input type="hidden" value="'.$edit->tid.'" name="tid" />
						<input type="hidden" value="'.$edit->cid.'" name="cid" />
						<input type="submit" class="input-submit border border-radius box-color edit-submit text-bold margin-top-5" value="Submit" />
					</div>
				</form>
			</div>
		';
		return $form;
	}
	
	public function buildContent(){
		pq('#bar-content')->replaceWith('
			<div id="bar-content" class="bar-center-main-content min-height padding-5">
				'.$this->leForm().'
			</div>
		');
	}
	
	function __construct($db, $theme = '', $userAgent, $parser, $file){
		parent::__construct($db, $theme, $userAgent, $parser, $file);
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Forum');
$site->run();
?>
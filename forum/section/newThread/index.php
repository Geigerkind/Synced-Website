<?php
require_once __DIR__ .'/../../../Init.php';

class BSite extends Site{
	
	private function goHome(){
		if(!$this->userAgent->isConfirmed()){
			header('Location: ../');
			exit();
		}
	}
	
	private function leForm(){
		$form .= '
			<div class="newThread-box border border-radius box-color min-height padding-5">
				<form action="{path}Modules/thread/addThread.php" method="post">
					<div class="newThread-top border-bottom">
						<label>Title of the thread:</label>
						<input type="text" class="input-text border border-radius box-color newThread-title" name="title" />
					</div>
					<div class="newThread-center border-bottom">
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
						<textarea name="text" class="thread-textarea" id="textedit"></textarea>
					</div>
					<div class="newThread-bottom min-height padding-5">
						<div class="newThread-adv-row border-bottom" onclick="toggle(\'newThread-adv-options\')">
							<div class="newThread-adv-row-title float-left">Advanced Options:</div>
							<div class="newThread-adv-row-toggle float-left"></div>
						</div>
						<div id="newThread-adv-options" class="newThread-adv-options min-height invisible">
							<div class="squaredThree">
								<input type="checkbox" value="None" id="squaredThree" name="poll" />
								<label for="squaredThree" onclick="toggle(\'newThread-poll\')"> </label>
								Add a poll
							</div>
		';
		if ($this->userAgent->isOfficer()){
			$form .= '
							<div class="squaredThree">
								<input type="checkbox" value="None" id="squaredThreee" name="sticky" />
								<label for="squaredThreee"> </label>
								Sticky
							</div>
			';
		}
		$form .= '
							<div id="newThread-poll" class="newThread-poll invisible">
								<div class="newThread-poll-row">
									<div class="newThread-poll-row-sep float-left">
										<label>Duration(days):</label>
										<input type="number" name="poll-duration" class="input-text border border-radius box-color" />
									</div>
									<div class="newThread-poll-row-sep float-left">
										<label>Argument 1:</label>
										<input type="text" name="poll-arg-1" class="input-text border border-radius box-color newThread-poll-arg" />
									</div>
								</div>
								<div class="newThread-poll-row">
									<div class="newThread-poll-row-sep float-left">
										<label>Argument 2:</label>
										<input type="text" name="poll-arg-2" class="input-text border border-radius box-color newThread-poll-arg" />
									</div>
									<div class="newThread-poll-row-sep float-left">
										<label>Argument 3:</label>
										<input type="text" name="poll-arg-3" class="input-text border border-radius box-color newThread-poll-arg" />
									</div>
								</div>
								<div class="newThread-poll-row">
									<div class="newThread-poll-row-sep float-left">
										<label>Argument 4:</label>
										<input type="text" name="poll-arg-4" class="input-text border border-radius box-color newThread-poll-arg" />
									</div>
									<div class="newThread-poll-row-sep float-left">
										<label>Argument 5:</label>
										<input type="text" name="poll-arg-5" class="input-text border border-radius box-color newThread-poll-arg" />
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" value="'.$this->userAgent->uid.'" name="uid" />
						<input type="hidden" value="'.$_GET['gtid'].'" name="gtid" />
						<input type="submit" class="input-submit border border-radius box-color newThread-submit text-bold margin-top-5" value="Submit" />
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
		$this->goHome();
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Forum');
$site->run();
?>
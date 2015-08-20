<?php
require_once __DIR__ .'/../../Init.php';

class BSite extends Site{
	
	private function goHome(){
		if(!$this->userAgent->isConfirmed()){
			header('Location: ../../');
			exit();
		}
	}
	
	private function leContent(){
		$content .= '
			<div class="newMedia-box border border-radius box-color min-height padding-10">
				<div class="newMedia-navbar border box-color border-top-radius">
					<ul>
						<li class="float-left" onclick="util_ButtonShow(\'stream\', \'video,screenshot\')">Stream</li>
						<li class="float-left border-left" onclick="util_ButtonShow(\'video\', \'stream,screenshot\')">Video</li>
						<li class="float-left border-left" onclick="util_ButtonShow(\'screenshot\', \'video,stream\')">Screenshot</li>
					</ul>
				</div>
				<div id="stream" class="newMedia-content border border-bottom-radius box-color min-height padding-5">
					<form action="{path}Modules/media/CreateStream.php" method="post">
						<div class="newMedia-content-title">
							<div class="newMedia-content-title-row">Title of the stream:</div>
							<div class="newMedia-content-title-row">
								<input type="text" name="title" id="title" class="input-text border border-radius box-color" />
							</div>
						</div>
						<div class="newMedia-content-title">
							<div class="newMedia-content-title-row">Twitch Name:</div>
							<div class="newMedia-content-title-row">
								<input type="text" name="twname" id="title" class="input-text border border-radius box-color" />
							</div>
						</div>
						<div class="newMedia-content-descr">
							<div class="newMedia-content-descr-row">Description of the stream:</div>
							<div class="newMedia-content-descr-row">
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
								<textarea class="thread-textarea media-textarea" id="textedit" name="descr"></textarea>
							</div>
						</div>
						<div class="newMedia-content-submit">
							<input type="hidden" name="uid" value="'.$this->userAgent->uid.'" />
							<input type="submit" value="Submit" class="input-submit border border-radius box-color" />
						</div>
					</form>
				</div>
				<div id="video" class="newMedia-content min-height invisible border border-bottom-radius box-color min-height padding-5">
					<form action="{path}Modules/media/CreateVideo.php" method="post">
						<div class="newMedia-content-title">
							<div class="newMedia-content-title-row">Title of the video:</div>
							<div class="newMedia-content-title-row">
								<input type="text" name="title" id="title" class="input-text border border-radius box-color" />
							</div>
						</div>
						<div class="newMedia-content-title">
							<div class="newMedia-content-title-row">Youtube-URL of the video:</div>
							<div class="newMedia-content-title-row">
								<input type="text" name="url" id="title" class="input-text border border-radius box-color" />
							</div>
						</div>
						<div class="newMedia-content-descr">
							<div class="newMedia-content-descr-row">Description of the video:</div>
							<div class="newMedia-content-descr-row">
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
								<textarea class="thread-textarea media-textarea" id="textedit" name="descr"></textarea>
							</div>
						</div>
						<div class="newMedia-content-submit">
							<input type="hidden" name="uid" value="'.$this->userAgent->uid.'" />
							<input type="submit" value="Submit" class="input-submit border border-radius box-color" />
						</div>
					</form>
				</div>
				<div id="screenshot" class="newMedia-content min-height invisible border border-bottom-radius box-color min-height padding-5">
					<form action="{path}Modules/media/CreateScreenshot.php" method="post" enctype="multipart/form-data">
						<div class="newMedia-content-title">
							<div class="newMedia-content-title-row">Title of the screenshot:</div>
							<div class="newMedia-content-title-row">
								<input type="text" name="title" id="title" class="input-text border border-radius box-color" />
							</div>
						</div>
						<div class="newMedia-content-upload">
							<div class="newMedia-content-title-row">Select a screenshot:</div>
							<div class="newMedia-content-title-row">
								<label class="media-file-label box-color-no-opa border border-radius">Select Image</label>
								<input type="file" name="file" id="file" class="input-text border border-radius box-color media-file" />
							</div>
						</div>
						<div class="newMedia-content-descr">
							<div class="newMedia-content-descr-row">Description:</div>
							<div class="newMedia-content-descr-row">
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
								<textarea class="thread-textarea media-textarea" id="textedit" name="descr"></textarea>
							</div>
						</div>
						<div class="newMedia-content-submit">
							<input type="hidden" name="uid" value="'.$this->userAgent->uid.'" />
							<input type="submit" value="Submit" class="input-submit border border-radius box-color" />
						</div>
					</form>
				</div>
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
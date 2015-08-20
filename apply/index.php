<?php
require_once __DIR__ .'/../Init.php';

class BSite extends Site{
	
	private function goHome(){
		if($this->userAgent->isLoggedIn()){
			header('Location: ../');
			exit();
		}
	}
	
	private function leContent(){
		$content .= '
			<form action="{path}Modules/account/newAccount.php" method="post">
				<div class="apply-box min-height border-radius box-color border">
					<div class="apply-box-title border-bottom box-color-no-opa border-top-radius text-bordered">Account:</div>
					<div class="apply-box-content min-height padding-5">
						<div class="apply-box-content-row">
							<div class="apply-box-content-row-sep float-left">
								<label>Username:</label>
								<input type="text" name="uname" class="input-text box-color border-radius border" />
							</div>
							<div class="apply-box-content-row-sep float-left">
								<label>Password:</label>
								<input type="password" name="pass" class="input-text box-color border-radius border" />
							</div>
						</div>
						<div class="apply-box-content-row">
							<div class="apply-box-content-row-sep float-left">
							</div>
							<div class="apply-box-content-row-sep float-left">
								<label>Confirm Password:</label>
								<input type="password" name="pass2" class="input-text box-color border-radius border" />
							</div>
						</div>
					</div>
				</div>
				<div class="apply-box min-height border-radius box-color border margin-top-5">
					<div class="apply-box-title border-bottom box-color-no-opa border-top-radius text-bordered">Character:</div>
					<div class="apply-box-content min-height padding-5">
						<div class="apply-box-content-row">
							<div class="apply-box-content-row-sep float-left">
								<label>Character Name:</label>
								<input type="text" name="charname" class="input-text box-color border-radius border" />
							</div>
							<div class="apply-box-content-row-sep float-left">
								<label>Race:</label>
								<select name="race" class="input-text box-color border-radius border">
									<option>Human</option>
									<option>Gnome</option>
									<option>Dwarf</option>
									<option>Night Elf</option>
								</select>
							</div>
						</div>
						<div class="apply-box-content-row">
							<div class="apply-box-content-row-sep float-left">
							</div>
							<div class="apply-box-content-row-sep float-left">
								<label>Class:</label>
								<select name="class" class="input-text box-color border-radius border">
									<option>Warrior</option>
									<option>Rogue</option>
									<option>Priest</option>
									<option>Hunter</option>
									<option>Druid</option>
									<option>Mage</option>
									<option>Warlock</option>
									<option>Paladin</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="apply-box min-height border-radius box-color border margin-top-5">
					<div class="apply-box-title border-bottom box-color-no-opa border-top-radius text-bordered">Some Information about you:</div>
					<div class="apply-box-content min-height padding-5">
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
						<textarea class="thread-textarea" id="textedit" name="text">
[color=#586f75][b][u][Personal Information][/u][/b][/color]
[b]Name:[/b]
[b]Age:[/b]
[b]Nationality:[/b]

[color=#586f75][b][u][Character Information][/u][/b][/color]
[b]Name:[/b]
[b]Level:[/b]
[b]Race:[/b]
[b]Class:[/b]
[b]Spec:[/b]
[b]Armory:[/b]

[color=#586f75][b][u][What kind of WoW experience do you have?][/u][/b][/color]
[b][u]Retail raiding experience (Primarily Vanilla):[/u][/b]

[b][u]Private Server experience (Primarily Vanilla):[/u][/b]

[color=#586f75][b][u][What is your goal on Kronos?][/u][/b][/color]

[color=#586f75][b][u][Can you attend to the majority of our raids?][/u][/b][/color]

[color=#586f75][b][u][How do you prepare for raids (Please be specific)?][/u][/b][/color]

[color=#586f75][b][u][Why do you think you\'re the right person for Synced?][/u][/b][/color]

[color=#586f75][b][u][Should we know anything else?][/u][/b][/color]
						</textarea>
						<div class="squaredThree">
							<input id="squaredThree" type="checkbox" checked="" name="check" value="None">
							<label for="squaredThree"></label>
							I\'ve read the <a class="sy-yellow" href="{host}/faq/">Rules</a> and accept them.
						</div>
						<input type="submit" value="Submit" class="input-submit box-color border border-radius" />
					</div>
				</div>
			</form>
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
$site->setContentName('Apply');
$site->run();
?>
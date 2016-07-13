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
[b][color=#778899]Start by telling us a bit about yourself, including your age, country of origin and details concerning your raiding experience.[/color][/b]

[b][color=#778899]Please link us your Armory profile.[/color][/b]

[b][color=#778899]Please link us what talent build(s) you are planning to employ in our raids. Explain your choice.[/color][/b]

[b][color=#778899]Please link us an image of your User Interface. Raid Frames, Raid AddOns and keybinds must all be properly on display.[/color][/b]

[b][color=#778899]1) We raid on Thursdays and Sundays, from 18:55 to 23:00 Server Time. What attendance would this schedule allow you to maintain (80%, 90%, etc.)?
Further, when new content is released, we may decide to raid on additional days or begin our raids at a different time, would you be able to rework your schedule accordingly?[/color][/b]

[b][color=#778899]2) Synced is a competitive guild, where a majority of members employ all consumables and world buffs available, are you willing and able to prepare in the same fashion? List the consumables and world buffs you would acquire prior to a raid in our guild.[/color][/b]

[b][color=#778899]3) We ask our Warriors, Rogues and Hunters to level up Engineering for the Gnomish Battle Chicken\'s 5% AS party-wide buff, for Thorium Grenades, etc. Are you willing to comply with this request if you play one of the above-mentioned? Other Classes are not expected to do the same, but we recommend it.[/color][/b]

[b][color=#778899]4) Do you have a microphone and are you willing to verbally communicate relevant information during raids?[/color][/b]

[b][color=#778899]5)  One of Synced\'s main goals is prevailing in the race for Realm Firsts. Would you be able to make this goal your own upon joining us?
And what are your own goals on Kronos?[/color][/b]

[b][color=#778899]6) Why do you think you\'re the right person for Synced?[/color][/b]

[b][color=#778899]7) It is entirely possible that the gap between AQ and Naxxramas will be as wide as the one between BWL and AQ. How succesful do you think you would be in maintaining your attendance and performance levels during such an interval?
How likely is it that your work or studies will severely interfere with your playtime sometime in the next 12 months?[/color][/b]

[b][color=#778899]8) Have you read our basic [url=http://synced-kronos.com/faq/]Social, Raid and Activity rules[/url] and agree to abide by them while in Synced?[/color][/b]

[b][color=#778899]9) If you think we should know anything else about you and should you have any questions concerning our guild, write it all below.[/color][/b]
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
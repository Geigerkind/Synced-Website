<?php
require_once '../../Init.php';

class BSite extends Site{
	
	private function leContent(){
		$content = '
				<div class="addi-container border border-radius box-color">
					<form action="{path}Modules/loot-system/add.php" method="post">
						<div class="addi-row border-bottom">
							<div class="addi-row-coloumn-left border-right float-left text-bordered">Insert the entry-id:</div>
							<div class="addi-row-coloumn-right float-left"><input type="text" name="entry" class="input-text box-color box-color border border-radius" /></div>
						</div>
						<div class="addi-row border-bottom">
							<div class="addi-row-coloumn-left border-right float-left text-bordered">Insert the item name:</div>
							<div class="addi-row-coloumn-right float-left"><input type="text" name="name" class="input-text box-color border border-radius" /></div>
						</div>
						<div class="addi-row">	
							<input type="hidden" value="'.$this->userAgent->uid.'" name="uid" />
							<input type="submit" value="Submit" id="submit" class="input-submit box-color border border-radius" />
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
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Loot-System - Add Item');
$site->run();
?>
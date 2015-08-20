<?php
require_once __DIR__ .'/../Init.php';

class BSite extends Site{
	
	private function leContent(){
		$content .= '
			<div class="rpcal-box border min-height padding-5 border-radius box-color">
				<div class="rpcal-littlebox">
					<form action="{path}RPCalculator/calculation/index.php" method="post">
						<div class="rpcal-row border-bottom">
							<div class="rpcal-left float-left border-right">
								Your current rankpoints:
							</div>
							<div class="rpcal-right float-left">
								<input type="text" class="input-text border border-radius box-color" name="startrp" />
							</div>
						</div>
						<div class="rpcal-row">
							<div class="rpcal-left float-left border-right">
								Estimated RP per week:
							</div>
							<div class="rpcal-right float-left">
								<input type="text" class="input-text border border-radius box-color" name="rpweek" />
							</div>
						</div>
						<div class="rpcal-row text-center">
							<input type="submit" value="Calculate" class="input-submit border border-radius box-color" />
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
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Rankpointscalculator');
$site->run();
?>
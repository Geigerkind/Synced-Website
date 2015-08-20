<?php
require_once __DIR__ .'/../../Init.php';

class BSite extends Site{
	private $EndRP = 0;
	private $StartRP = 0;
	private $RPWeek = 13000;
	private $i = 1;
	
	private function calculateStartRP(){
		if ($this->i != 1){
			$this->StartRP = $this->EndRP;
		}
		return ceil($this->StartRP);
	}
	
	private function calculateEndRP(){
		$this->EndRP = $this->StartRP - $this->StartRP*0.2 + $this->RPWeek;
		return ceil($this->EndRP);
	}
	
	private function calculateDifference(){
		return ceil($this->EndRP-$this->StartRP);
	}
	
	private function calculateRank(){
		return ceil(($this->EndRP/5000) + 1);
	}
	
	private function leContent(){
		$content .= '
			<div class="rpcal-box border min-height padding-5 border-radius box-color">
				<div class="rpcal-top">
					<div class="rpcal-image border-radius"><img class="rpcal-image border-radius" src="img/cal.php?rpweek='.$this->RPWeek.'&startrp='.$this->StartRP.'" /></div>
				</div>
				<div class="rpcal-bottom min-height">
					<div class="rpcal-table border-radius min-height box-color border">
						<div class="rpcal-row">
							<div class="rpcal-caloumn text-bordered text-bold float-left">Week</div>
							<div class="rpcal-caloumn text-bordered text-bold float-left">Start RP</div>
							<div class="rpcal-caloumn text-bordered text-bold float-left">End RP</div>
							<div class="rpcal-caloumn text-bordered text-bold float-left">Difference</div>
							<div class="rpcal-caloumn text-bordered text-bold float-left">Rank</div>
						</div>
		';
		for ($this->i=1; $this->i <= 10; $this->i++){
			$content .= '
						<div class="rpcal-row">
							<div class="rpcal-caloumn float-left">'.$this->i.'</div>
							<div class="rpcal-caloumn float-left">'.$this->calculateStartRP().'</div>
							<div class="rpcal-caloumn float-left">'.$this->calculateEndRP().'</div>
							<div class="rpcal-caloumn float-left">'.$this->calculateDifference().'</div>
							<div class="rpcal-caloumn float-left">'.$this->calculateRank().'</div>
						</div>
			';
		}
		$content .= '
					</div>
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
		$this->StartRP = $_POST["startrp"];
		$this->RPWeek = $_POST["rpweek"];
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Rankpointscalculation');
$site->run();
?>
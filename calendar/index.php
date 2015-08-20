<?php
require_once __DIR__ .'/../Init.php';

class BSite extends Site{
	private $curTimestamp = 0;
	
	private function goHome(){
		if(!isset($_GET["date"]) OR empty($_GET["date"])){
			header('Location: ../');
		}
	}
	
	private function getDate($a){
		return date('j.n.Y', strtotime(date('j.m.Y', $this->curTimestamp).'+'.$a.' month'));
	}
	
	private function getDay($ts){
		return date('j', $ts);
	}
	
	private function getMonthName($time){
		return date('F', $time);
	}
	
	private function getYear($time){
		return date('Y', $time);
	}
	
	private function getCurTimestamp(){
		$this->curTimestamp = strtotime($_GET["date"]);
	}
	
	private function getFirstDay(){
		$k = date('N', strtotime(date('j.m.Y', $this->curTimestamp)));
		if($k != 7)
			return -$k;
		else
			return 0;
	}
	
	private function leContent(){
		$content .= '
			<div class="calendar-box border border-radius padding-5 min-height">
				<div class="row-days border-bottom text-bold">
					<div class="row-days-column float-left">SUN</div>
					<div class="row-days-column border-left float-left">MON</div>
					<div class="row-days-column border-left float-left">TUE</div>
					<div class="row-days-column border-left float-left">WED</div>
					<div class="row-days-column border-left float-left">THU</div>
					<div class="row-days-column border-left float-left">FRI</div>
					<div class="row-days-column border-left float-left">SAT</div>
				</div>
		';
		for($ii = 0; $ii < 5; $ii++){
			$borderL = '';
			$borderB = '';
			if($ii != 4)
				$borderB = 'border-bottom';
			$content .= '
				<div class="row-ca-day '.$borderB.'">
			';
			for($i = 0; $i < 7; $i++){
				if($this->userAgent->isConfirmed())
					$row = $this->db->query('SELECT eventid, title, img FROM calender_event WHERE date = "'.date('d.m.Y', $this->curTimestamp + ($this->getFirstDay()+($ii*7)+$i)*86400).'"')->fetch();
				if($i != 0)
					$borderL = 'border-left';
				$content .= '
					<a href="{host}/calendar/event/?date='.date('d.m.Y', $this->curTimestamp + ($this->getFirstDay()+($ii*7)+$i)*86400).'">
						<div class="row-ca-day-column '.$borderL.' padding-5 float-left">
							<div class="row-ca-day-column-count text-bold">'.$this->getDay($this->curTimestamp + ($this->getFirstDay()+($ii*7)+$i)*86400).'</div>
							<div class="row-ca-day-column-title">'.$row->title.'</div>
							<div class="row-ca-day-column-time"></div>
						</div>
					</a>
				';
			}
			$content .= '
				</div>
			';
		}
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
		$this->getCurTimestamp();
		$this->setContentName('Calendar - '.$this->getMonthName($this->curTimestamp).' - '.$this->getYear(time()).' <a class="sy-yellow" href="{host}/calendar/?date='.$this->getDate(-1).'">«</a> <a class="sy-yellow" href="{host}/calendar/?date='.$this->getDate(1).'">»</a>');
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->run();
?>
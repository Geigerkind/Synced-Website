<?php
require_once __DIR__ .'/../Init.php';

class BSite extends Site{
	
	private function getParagraphs(){
		return $this->db->query('SELECT * FROM paragraph');
	}
	
	private function getParagraphInformation($id){
		return $this->db->query('SELECT * FROM paragraph_rule WHERE pid ='.$id);
	}
	
	private function leContent(){
		$content .= '
				<div class="faq-box min-height border border-radius box-color padding-10">
			';
		foreach($this->getParagraphs() AS $val){
			$i = 0;
			$content .= '
					<div class="faq-title-row border-bottom">'.$val->name.'</div>
					<div class="faq-rule-container padding-5 min-height box-color border border-bottom-radius">
			';
			foreach($this->getParagraphInformation($val->id) AS $row){
				$i++;
				$content .= '
						<div class="faq-rule-row border-bottom box-color" onclick="util_switch(\'faq-'.$row->name.'\')">
							<div class="faq-rule-row-count float-left">§'.$i.'</div>
							<div class="faq-rule-row-name float-left">'.$row->name.'</div>
							<div class="faq-rule-row-toggle float-left"></div>
						</div>
						<div id="faq-'.$row->name.'" class="faq-rule-box border border-bottom-radius padding-5 min-height box-color invisible">
							'.$this->parser->parse($row->content)->getAsHtml().'
						</div>
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
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('FAQ');
$site->run();
?>
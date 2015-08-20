<?php

require_once __DIR__ .'/Init.php';

class BSite extends Site{
	
	public function buildContent(){
		foreach($this->db->query('SELECT a.title, a.descr, b.uid, b.name, a.date, a.gtid, a.tid FROM forum_topics a JOIN user b ON a.uid = b.uid WHERE a.gtid = 3 ORDER BY a.tid DESC LIMIT 5') as $row){
			$content .= '
				<div id="bar-content" class="bar-center-main-content min-height padding-5">
					<div class="news-container border border-radius min-height">
						<div class="news-container-title box-color-no-opa border-bottom text-bordered border-top-radius">'.$row->title.'</div>
						<div class="news-container-content box-color min-height padding-10">
							'.$this->parser->parse($row->descr)->getAsHtml().'
							<div class="news-end-box border border-radius padding-left-5 margin-top-10 box-color">Posted by <a href="{path}account/?uid='.$row->uid.'" class="sy-yellow">'.$row->name.'</a> on '.$row->date.'<br /> <a href="{path}forum/section/thread/?tid='.$row->tid.'" class="sy-yellow">Read more...</a></div>
						</div>
					</div>
				</div>
			';
		}
		pq('#bar-content')->replaceWith($content);
	}
	
	function __construct($db, $theme = '', $userAgent, $parser, $file){
		parent::__construct($db, $theme, $userAgent, $parser, $file);
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->run();
?>
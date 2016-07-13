<?php
require_once __DIR__ .'/../Init.php';

class BSite extends Site{
	
	private function forum(){
		$section = '<div class="forum-link-handler border border-radius box-color-no-opa padding-left-5"><a href="{host}/forum/">Forum</a> /</div>';

		foreach($this->db->query('SELECT * FROM forum_section WHERE permission <= '.$this->userAgent->rank.' ORDER BY prio DESC') AS $row){
			$section .= '
				<div id="" class="forum-title-row box-color-no-opa border">
					<div class="forum-title-row-left float-left padding-left-5">[EN] '.$row->title.'</div>
					<div class="forum-title-row-center float-left">Threads / Posts</div>
					<div class="forum-title-row-right float-left">Last Post</div>
				</div>
			';
			foreach($this->db->query('SELECT a.gtid, a.img, a.title, a.desc, (SELECT COUNT(b.tid) FROM forum_topics b WHERE gtid = a.gtid) as threads, (SELECT COUNT(c.cid) FROM forum_topics_comment c WHERE c.tid IN (SELECT d.tid FROM forum_topics d WHERE d.gtid = a.gtid)) AS posts FROM forum_section_topics a WHERE a.sid = '.$row->sid.' AND readpermission <= '.$this->userAgent->rank.' ORDER BY prio DESC') AS $rowInfo){
				$rowLatest = $this->db->query('SELECT a.cid, b.gtid, a.tid, a.uid, b.title, a.date, c.name, d.class, (SELECT COUNT(cid) FROM forum_topics_comment WHERE tid = a.tid) AS pages FROM forum_topics_comment a JOIN forum_topics b ON a.tid = b.tid JOIN user c ON a.uid = c.uid JOIN user_char d ON d.uid = c.uid WHERE d.mainchar = 1 AND a.cid = (SELECT MAX(cid) FROM forum_topics_comment JOIN forum_topics ON forum_topics_comment.tid = forum_topics.tid WHERE gtid = '.$rowInfo->gtid.')')->fetch();
				if(!empty($rowLatest->name)){ $latest = 'by <a href="#" class="color-'.strtolower($rowLatest->class).'">'.$rowLatest->name.'</a>'; }else{ $latest = 'None'; }
				$section .= '
					<div id="section-'.$rowInfo->title.'" class="forum-row border box-color">
						<div class="forum-row-left float-left">
							<div class="forum-row-left-pic-handler padding-5 float-left">
								<div class="forum-row-left-pic-handler-pic" style="background-image: url(\'img/'.$rowInfo->img.'.png\');"></div>
							</div>
							<div class="forum-row-left-info float-left">
								<div class="forum-row-left-info-title text-bold"><a href="{host}/forum/section/?gtid='.$rowInfo->gtid.'" class="sy-yellow">'.$rowInfo->title.'</a></div>
								<div class="forum-row-left-info-content">'.$rowInfo->desc.'</div>
							</div>
						</div>
						<div class="forum-row-center float-left">
							<div class="forum-row-center-row">
								<div class="forum-row-center-row-left float-left">Threads:</div>
								<div class="forum-row-center-row-right float-left">'.$rowInfo->threads.'</div>
							</div>
							<div class="forum-row-center-row">
								<div class="forum-row-center-row-left float-left">Posts:</div>
								<div class="forum-row-center-row-right float-left">'.$rowInfo->posts.'</div>
							</div>
						</div>
						<div class="forum-row-right float-left">
							<div class="forum-row-right-row"><a href="{host}/forum/section/thread/?tid='.$rowLatest->tid.'&page='.ceil($rowLatest->pages/10).'#comment-'.$rowLatest->cid.'" class="sy-yellow">'.$this->shortenString(18, $rowLatest->title).'</a></div>
							<div class="forum-row-right-row">'.$latest.'</div>
							<div class="forum-row-right-row">'.$rowLatest->date.'</div>
						</div>
					</div>
				';
			}
		}
		
		return $section;
	}
	
	public function buildContent(){
		pq('#bar-content')->replaceWith('
			<div id="bar-content" class="bar-center-main-content min-height padding-5">
				'.$this->forum().'
			</div>
		');
	}
	
	function __construct($db, $theme = '', $userAgent, $parser, $file){
		parent::__construct($db, $theme, $userAgent, $parser, $file);
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Forum');
$site->run();
?>
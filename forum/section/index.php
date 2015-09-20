<?php
require_once __DIR__ .'/../../Init.php';

class BSite extends Site{
	private $curPage = null;
	private $threads = null;
	
	private function state($confirmed){
		switch($confirmed){
			case 0 :
				return '<span class="color-on-hold">[On Hold] </span>';
				break;
			case 1 :
				return '<span class="color-confirmed">[Confirmed] </span>';
				break;
			case 2 :
				return '<span class="color-declined">[Declined] </span>';
				break;
			case 3 : 
				return '<span class="color-kicked">[Kicked] </span>';
				break;
			default:
				return '<span class="color-unknown crossed">[Unknown] </span>';
				break;
		}
	}
	
	private function goHome($vp){
		if(!$this->userAgent->isConfirmed() AND $vp != -1){
			header('Location: ../');
			exit();
		}
	}
	
	private function getCurPage(){
		$page = ceil($this->threads / 20);
		if(empty($_GET['page']) OR $_GET['page'] < 0){ 
			return 1; 
		}else{ 
			if($_GET['page'] > $page){ 
				return $page; 
			}else{ 
				return $_GET['page']; 
			} 
		}
	}
	
	private function postHandler($gtid){
		$page = ceil($this->threads / 20);
		if($this->curPage - 1 <= 0){ $back = $this->curPage; }else{ $back = $this->curPage - 1; }
		if($this->curPage + 1 > $page){ $next = $this->curPage; }else{ $next = $this->curPage + 1; }
		
		if((!in_array($_GET['gtid'], array(37, 3)) AND $this->userAgent->hasAccount()) OR $this->userAgent->isOfficer())
			$newThread = '<div class="action-row-button button border box-color border-radius"><a href="{host}/forum/section/newThread/?gtid='.$_GET["gtid"].'">Post a new thread</a></div>';
		
		return '
				<div class="section-action-row">
					<div class="section-action-row-left padding-left-5 float-left">
						'.$newThread.'
					</div>
					<div class="section-action-row-right float-left">
						<div class="page-handler border box-color">
							<div class="page-handler-index float-left">Page '.$this->curPage.' of '.$page.'</div>
							<a href="{host}/forum/section/?gtid='.$gtid.'&page=1"><div class="page-handler-first float-left">First</div></a>
							<a href="{host}/forum/section/?gtid='.$gtid.'&page='.$back.'"><div class="page-handler-before float-left"></div></a>
							<a href="{host}/forum/section/?gtid='.$gtid.'&page='.$next.'"><div class="page-handler-next float-left"></div></a>
							<a href="{host}/forum/section/?gtid='.$gtid.'&page='.$page.'"><div class="page-handler-last float-left">Last</div></a>
						</div>
					</div>
				</div>
		';
	}
	
	private function forumSection(){
		$row = $this->db->query('SELECT a.gtid, a.title, b.permission FROM forum_section_topics a JOIN forum_section b ON a.sid = b.sid WHERE a.gtid = '.$_GET['gtid'])->fetch();
		$this->goHome($row->permission);
			$section = '<div class="forum-link-handler border border-radius box-color-no-opa padding-left-5"><a href="{host}/forum/">Forum</a> / <a href="{host}/forum/section/?gtid='.$row->gtid.'">'.$row->title.'</a></div>';
			$section .= '
				<div id="" class="forum-title-row box-color-no-opa border">
					<div class="forum-title-row-left float-left padding-left-5">[EN] '.$row->title.'</div>
					<div class="forum-title-row-center float-left">Replies / Views</div>
					<div class="forum-title-row-right float-left">Last Post</div>
				</div>
			';
			$this->threads = $this->db->query('SELECT tid FROM forum_topics WHERE gtid = '.$row->gtid)->rowCount();
			$this->curPage = $this->getCurPage();
			foreach($this->db->query('SELECT a.img, a.title, a.tid, b.uid, b.name, a.date, a.gtid, b.confirmed, d.class, a.hits, (SELECT COUNT(vid) FROM forum_views WHERE tid = a.tid) as views, (SELECT COUNT(cid) FROM forum_topics_comment WHERE tid = a.tid) as replies FROM forum_topics a JOIN user b ON a.uid = b.uid JOIN forum_topics_comment c ON a.tid = c.tid JOIN user_char d ON d.uid = b.uid WHERE mainchar = 1 AND gtid = '.$row->gtid.' AND cid = (SELECT MAX(cid) FROM forum_topics_comment WHERE tid = a.tid) ORDER BY sticky DESC, b.confirmed, cid DESC LIMIT '.(($this->curPage-1)*20).', 20') AS $rowInfo){
				$rowLatest = $this->db->query('SELECT a.cid, a.title, a.date, b.gtid, a.uid, c.name, a.tid, d.class, (SELECT COUNT(cid) FROM forum_topics_comment WHERE tid = a.tid) AS page FROM forum_topics_comment a JOIN forum_topics b ON a.tid = b.tid JOIN user c ON a.uid = c.uid JOIN user_char d ON d.uid = c.uid WHERE d.mainchar = 1 AND a.cid = (SELECT max(cid) FROM forum_topics_comment WHERE tid = '.$rowInfo->tid.')')->fetch();
				if($row->gtid == 37)
					$app = $this->state($rowInfo->confirmed);
				$section .= '
					<div id="section-'.$rowInfo->title.'" class="forum-row border box-color">
						<div class="forum-row-left float-left">
							<div class="forum-row-left-pic-handler padding-5 float-left">
								<div class="forum-row-left-pic-handler-pic" style="background-image: url(\'{path}forum/img/'.$rowInfo->img.'.png\');"></div>
							</div>
							<div class="forum-row-left-info float-left">
								<div class="forum-row-left-info-title text-bold"><a href="{host}/forum/section/thread/?tid='.$rowInfo->tid.'" class="sy-yellow">'.$app.$this->shortenString(35, $rowInfo->title).'</a></div>
								<div class="forum-row-left-info-content">Started by <a href="#" class="color-'.strtolower($rowInfo->class).'">'.$rowInfo->name.'</a>, '.$rowInfo->date.'</div>
							</div>
						</div>
						<div class="forum-row-center float-left">
							<div class="forum-row-center-row-section">
								<div class="forum-row-center-row-left float-left">Replies:</div>
								<div class="forum-row-center-row-right float-left">'.$rowInfo->replies.'</div>
							</div>
							<div class="forum-row-center-row-section">
								<div class="forum-row-center-row-left float-left">Seen by:</div>
								<div class="forum-row-center-row-right float-left">'.$rowInfo->views.'</div>
							</div>
							<div class="forum-row-center-row-section">
								<div class="forum-row-center-row-left float-left">Hits:</div>
								<div class="forum-row-center-row-right float-left">'.$rowInfo->hits.'</div>
							</div>
						</div>
						<div class="forum-row-right float-left">
							<div class="forum-row-right-row"><a href="{host}/forum/section/thread/?tid='.$rowInfo->tid.'#comment-'.$rowLatest->cid.'" class="sy-yellow">'.$this->shortenString(15, $rowLatest->title).'</a></div>
							<div class="forum-row-right-row">by <a href="#" class="color-'.strtolower($rowLatest->class).'">'.$rowLatest->name.'</a></div>
							<div class="forum-row-right-row">'.$rowLatest->date.'</div>
						</div>
					</div>
				';
			}
			$section .= $this->postHandler($row->gtid);
		return $section;
	}
	
	public function buildContent(){
		pq('#bar-content')->replaceWith('
			<div id="bar-content" class="bar-center-main-content min-height padding-5">
				'.$this->forumSection().'
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
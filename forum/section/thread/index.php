<?php
require_once __DIR__ .'/../../../Init.php';

class BSite extends Site{
	private $curPage = null;
	private $threads = null;
	
	private function hasSeen(){
		if($this->db->query('SELECT * FROM forum_views WHERE tid = '.$_GET["tid"].' AND uid ='.$this->userAgent->uid)->rowCount() == 0){
			$this->db->query('INSERT INTO forum_views (tid, uid) VALUES ('.$_GET["tid"].', '.$this->userAgent->uid.')');
		}
	}
	
	private function goHome($vp){
		if(!$this->userAgent->isConfirmed() AND $vp != -1){
			header('Location: ../../');
			exit();
		}
	}
	
	private function state($confirmed){
		switch($confirmed){
			case 0 :
				return '<span class="color-on-hold">On Hold</span>';
				break;
			case 1 :
				return '<span class="color-confirmed">Confirmed</span>';
				break;
			case 2 :
				return '<span class="color-declined">Declined</span>';
				break;
			case 3 : 
				return '<span class="color-kicked">Kicked</span>';
				break;
			default:
				return '<span class="color-unknown crossed">Unknown</span>';
				break;
		}
	}
	
	private function getCurPage(){
		$page = ceil($this->threads / 10);
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
	
	private function postHandler($tid){
		$page = ceil($this->threads / 10);
		if($this->curPage - 1 <= 0){ $back = $this->curPage; }else{ $back = $this->curPage - 1; }
		if($this->curPage + 1 > $page){ $next = $this->curPage; }else{ $next = $this->curPage + 1; }
		
		return '
				<div class="section-action-row">
					<div class="section-action-row-left padding-left-5 float-left"></div>
					<div class="section-action-row-right float-left">
						<div class="page-handler border box-color">
							<div class="page-handler-index float-left">Page '.$this->curPage.' of '.$page.'</div>
							<a href="{host}/forum/section/thread/?tid='.$tid.'&page=1"><div class="page-handler-first float-left">First</div></a>
							<a href="{host}/forum/section/thread/?tid='.$tid.'&page='.$back.'"><div class="page-handler-before float-left"></div></a>
							<a href="{host}/forum/section/thread/?tid='.$tid.'&page='.$next.'"><div class="page-handler-next float-left"></div></a>
							<a href="{host}/forum/section/thread/?tid='.$tid.'&page='.$page.'"><div class="page-handler-last float-left">Last</div></a>
						</div>
					</div>
				</div>
		';
	}
	
	private function raiseHits(){
		if(!isset($_COOKIE['thread-'.$_GET["tid"]])){
			$this->db->query('UPDATE forum_topics SET hits = (hits + 1) WHERE tid ='.$_GET["tid"]);
			setCookie('thread-'.$_GET["tid"], 'true', time() + 60);
		}
	}
	
	private function addAppSpecial($gtid, $i, $confirmed, $tid, $uid){
		if($gtid == 37 AND $i == 1 AND ($this->userAgent->isOfficer() OR $this->userAgent->isClassLeader()) AND $uid != 8){
			$appSpecial .= '
				<div class="thread-user-bottom-row min-height">
					<form action="{path}Modules/thread/evalState.php" method="post">
						<input type="hidden" value="'.$uid.'" name="uid" />
						<input type="hidden" value="'.$this->userAgent->uid.'" name="actionUid" />
						<input type="hidden" value="'.$_GET["tid"].'" name="tid" />
						<div class="app-state text-bold float-left">'.$this->state($confirmed).'</div>
						<div class="app-state-top button border box-color float-left"><input type="submit" name="submit" value="1" class="invisible" id="confirm" /><a onclick="document.getElementById(\'confirm\').click()">Confirm</a></div>
						<div class="app-state-top button margin-left-5 border box-color float-left"><input type="submit" name="submit" value="2" class="invisible" id="decline" /><a onclick="document.getElementById(\'decline\').click()">Decline</a></div>
						<div class="app-state-bottom button margin-top-5 border box-color float-left"><input type="submit" name="submit" value="0" class="invisible" id="on-hold" /><a onclick="document.getElementById(\'on-hold\').click()">On Hold</a></div>
					</form>
				</div>
			';
			return $appSpecial;
		}
		return '';
	}
	
	private function filterIssues($text){
		return str_replace(array('&nbsp;', '&Acirc;', '&Atilde;', '…', '†', '˜', '&iquest;', '&frac12;', '&Acirc;', '&iuml;'), '', $text);
	}
	
	private function forumThread(){
		$mRow = $this->db->query('SELECT a.poll, d.permission, a.title AS atitle, b.title AS btitle, a.tid, c.date as polldate, c.duration, b.gtid, (SELECT COUNT(cid) FROM forum_topics_comment WHERE tid = a.tid) AS comments FROM forum_topics a JOIN forum_section_topics b ON a.gtid = b.gtid JOIN forum_section d ON b.sid = d.sid LEFT JOIN forum_topics_poll c ON a.tid = c.tid WHERE a.tid ='.$_GET['tid'])->fetch();
		$this->goHome($mRow->permission);
		$this->threads = $mRow->comments;
		$this->curPage = $this->getCurPage();
		if($this->userAgent->isOfficer())
			$trashThread .= '<a href="{host}/../Modules/thread/deleteThread.php?tid='.$mRow->tid.'&gtid='.$mRow->gtid.'"><img class="centred-margin trashcan" src="{host}/forum/section/thread/img/trashcan.png" /></a>';
		$thread .= '
			<div class="forum-link-handler border border-radius box-color-no-opa">
				<div class="forum-link-handler-left padding-left-5 float-left"><a href="{host}/forum/">Forum</a> / <a href="{host}/forum/section/?gtid='.$mRow->gtid.'">'.$mRow->btitle.'</a> / <a href="{host}/forum/section/thread/?tid='.$mRow->tid.'">'.$mRow->atitle.'</a></div>
				<div class="forum-link-handler-right float-left">'.$trashThread.'</div>
			</div>
			';
		if($mRow->poll){
			$thread .= '
				<div id="poll" class="poll-container min-height border border-radius box-color padding-5">
					<form action="{path}Modules/thread/evalPoll.php" method="post">
			';
			foreach($this->db->query('SELECT b.arg, b.argid, a.pollid FROM forum_topics_poll a JOIN forum_topics_poll_args b ON a.pollid = b.pollid WHERE a.tid = '.$mRow->tid) AS $poll){
				$voice = $this->db->query('SELECT voiceid FROM forum_topics_poll_args_voice WHERE argid = '.$poll->argid)->rowCount();
				$voiceOverall = $this->db->query('SELECT a.voiceid FROM forum_topics_poll_args_voice a JOIN forum_topics_poll_args b ON a.argid = b.argid WHERE b.pollid = '.$poll->pollid)->rowCount();
				$p++;
				$thread .= '
						<div class="poll-row">
							<div class="poll-row-arg float-left"></div>
							<div class="poll-row-selector float-left">
								<div class="squared-'.$p.'">
									<input type="checkbox" value="None" id="squared-'.$p.'" name="poll-checkbox-'.$poll->argid.'" />
									<label for="squared-'.$p.'"></label>
								</div>
								<input type="hidden" value="'.$poll->argid.'" name="poll-arg-'.$p.'" />
							</div>
							<div class="poll-row-progress border-radius border" style="background-color: rgba(240, 169, 5, 0.6); width: '.($voice*676/$voiceOverall).'px;"></div>
							<div class="poll-row-progress-value text-bold">
								<div class="poll-row-progress-arg padding-left-5 float-left">'.$poll->arg.'</div>
								<div class="poll-row-progress-eval float-left">'.((int)round($voice/$voiceOverall*100)).'% ('.$voice.')</div>
							</div>
						</div>
				';
			}
			$thread .= '
					<input type="hidden" name="eval" value="'.$p.'" />
					<input type="hidden" name="uid" value="'.$this->userAgent->uid.'" />
					<input type="hidden" name="tid" value="'.$mRow->tid.'" />
			';
			if(strtotime($mRow->polldate) + 60*60*24*$mRow->duration >= time()){
				$thread .= '
					<input type="submit" value="Vote now!" class="input-submit border border-radius box-color text-bold poll-submit-button" />
				';
			}
			$thread .= '
					</form>
				</div>
			';
		}
		$thread .= '
			<div class="thread-main-box min-height border box-color border-radius">
		';
		$i = (($this->curPage-1)*10);
		foreach($this->db->query('SELECT a.tid, a.cid, a.title, a.text, a.date, b.name, b.confirmed, a.uid, d.class, c.rankname, b.img, d.charName, b.posts FROM forum_topics_comment a JOIN user b ON a.uid = b.uid JOIN ranks c ON b.rank = c.rid JOIN user_char d ON b.uid = d.uid WHERE d.mainchar = 1 AND a.tid = '.$_GET["tid"].' ORDER BY cid LIMIT '.(($this->curPage-1)*10).', 10') AS $row){
			if($i > 0 AND $this->userAgent->isOfficer())
				$trashComment = '<a href="{host}/../Modules/thread/deleteComment.php?cid='.$row->cid.'&tid='.$mRow->tid.'"><img class="centred-margin trashcan" src="{host}/forum/section/thread/img/trashcan.png" /></a>';
			if($this->userAgent->isClassLeader() OR $this->userAgent->uid == $row->uid)
				$edit = '<a href="{host}/forum/section/thread/edit/?cid='.$row->cid.'">edit</a>';
			$i++;
			$thread .= '
				<div id="comment-'.$row->cid.'" class="thread-box min-height border-bottom box-color">
					<div class="thread-user-box min-height float-left padding-10">
						<div class="thread-user-box-name text-bold"><a href="{path}account/?uid='.$row->uid.'" class="color-'.strtolower($row->class).'">'.$row->name.'</a></div>
						<div class="thread-user-pic-box">
							<div class="thread-user-pic centred-margin border img-shadow border-radius box-color" style="background-image: url(\'{host}/../Database/img/User/'.$row->img.'.png\');"></div>
						</div>
						<div class="thread-user-bottom min-height">
							<div class="thread-user-bottom-row">
								<div class="thread-user-bottom-row-left float-left">Main:</div>
								<div class="thread-user-bottom-row-right float-left">'.$row->charName.'</div>
							</div>
							<div class="thread-user-bottom-row min-height">
								<div class="thread-user-bottom-row-left float-left">Class:</div>
								<div class="thread-user-bottom-row-right float-left">'.$row->class.'</div>
							</div>
							<div class="thread-user-bottom-row min-height">
								<div class="thread-user-bottom-row-left float-left">Rank:</div>
								<div class="thread-user-bottom-row-right float-left">'.$row->rankname.'</div>
							</div>
							<div class="thread-user-bottom-row min-height">
								<div class="thread-user-bottom-row-left float-left">Posts:</div>
								<div class="thread-user-bottom-row-right float-left">'.$row->posts.'</div>
							</div>
							'.$this->addAppSpecial($mRow->gtid, $i, $row->confirmed, $mRow->tid, $row->uid).'
						</div>
					</div>
					<div class="thread-text-box min-height float-left border-left">
						<div class="thread-text-box-title border-bottom">
							<div class="thread-text-box-title-left text-bold padding-left-5 float-left">'.$this->shortenString(50, $row->title).'</div>
							<div class="thread-text-box-title-center float-left">'.$trashComment.'</div>
							<div class="thread-text-box-title-right float-left">#'.$i.'</div>
						</div>
						<div class="thread-text-box-text padding-5 border-bottom">'.$this->parser->parse($this->filterIssues($row->text))->getAsHtml().'</div>
						<div class="thread-text-box-bottom">
							<div class="thread-text-box-bottom-left padding-left-5 float-left">Posted on '.$row->date.'</div>
							<div class="thread-text-box-bottom-right float-left">'.$edit.'</div>
						</div>
					</div>
				</div>
			';
		}
		$thread .= '
			</div>
			'.$this->postHandler($mRow->tid).'
		';
		if($this->userAgent->hasAccount()){
			$thread .= '
			<div class="thread-textarea-box">
				<form action="{path}Modules/thread/addPost.php" method="post">
				<input type="hidden" name="uid" value="'.$this->userAgent->uid.'" />
				<input type="hidden" name="tid" value="'.$mRow->tid.'" />
					<div id="thread-title" class="thread-textarea-box-adv-title invisible">
						<input type="text" value="RE: '.$mRow->atitle.'" name="title" class="thread-adv-title input-text box-color border border-radius" />
					</div>
					<div class="thread-textarea-box-content">
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
						<textarea name="text" class="thread-textarea" id="textedit"></textarea>
					</div>
					<div class="thread-textarea-box-button">
						<div class="thread-textarea-box-sep float-left">
							<div class="button border border-radius thread-button centred-margin"><a href="#thread-title" onclick="toggle(\'thread-title\')">Advanced Reply</a></div>
						</div>
						<div class="thread-textarea-box-sep float-left">
							<input type="submit" class="input-submit border border-radius thread-button centred-margin text-bold" value="Post Reply" />
						</div>
					</div>
				</form>
			</div>
			';
		}
		$thread .= '
		';
		return $thread;
	}
	
	public function buildContent(){
		pq('#bar-content')->replaceWith('
			<div id="bar-content" class="bar-center-main-content min-height padding-5">
				'.$this->forumThread().'
			</div>
		');
	}
	
	function __construct($db, $theme = '', $userAgent, $parser, $file){
		parent::__construct($db, $theme, $userAgent, $parser, $file);
		$this->raiseHits();
		$this->hasSeen();
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Forum');
$site->run();
?>
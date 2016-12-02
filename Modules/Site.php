<?php
define('ROOT', dirname(__FILE__));

abstract class Site extends phpquery {
	public $db = null;
	private $doc = null;
	public $userAgent = null;
	public $parser = null;
	private $file = '';
	
	public abstract function buildContent();
	
	public function shortenString($num, $string){
		if (strlen($string) >= $num){
			return substr($string, 0, -($string - $num)).'...';
		}else{
			return $string;
		}
	}
	
	private function addCss(){
		if($_SERVER[HTTP_HOST] == '127.0.0.1')
			$pat = '\\';
		else{
			$pat = '/';
		}
		$mes = explode($pat, $this->file);
		$url = $mes[sizeOf($mes)-1];
		pq('head')->append('<link rel="stylesheet" type="text/css" href="{path}Themes/Default/css/'.$url.'.css" />');
	}
	
	public function setContentName($name){
		pq('#content-container-title')->replaceWith('<div id="content-container-title" class="bar-center-main-title box-color-no-opa text-bordered">- '.$name.'</div>');
	}
	
	private function paramKey($key, $chest){
		$this->doc = str_replace(array('%7B'.$key.'%7D', '{'.$key.'}'), $chest, $this->doc);
	}
	private function addParams(){
		$this->paramKey('location', 'http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);
		$this->paramKey('host', $r = ($_SERVER[HTTP_HOST] == '127.0.0.1') ? 'http://127.0.0.1/SWebsite' : 'http://synced-kronos.com');
		$this->paramKey('date', date('01.n.Y'));
	}
	
	private function setLinks(){
		if($_SERVER[HTTP_HOST] == '127.0.0.1')
			$pat = '\\';
		else{
			$pat = '/';
		}
		$mes = explode($pat, $this->file);
		$pos = sizeOf($mes) - array_search('SWebsite', $mes);
		for($i = 1; $i < $pos; $i++){ $path .= '../'; }
		$this->doc = str_replace(array('%7Bpath%7D', '{path}'), $path, $this->doc);
	}
	
	private function loadLatestPosts(){
		foreach($this->db->query('SELECT * FROM latest_posts a WHERE permission <= '.$this->userAgent->rank.' ORDER BY a.id DESC LIMIT 20') as $row){
			if ($row->type == 0){
				$temp = $this->db->query('SELECT a.cid, b.name, c.title, a.date, a.uid, c.gtid, a.tid, f.class FROM forum_topics_comment a JOIN user b ON a.uid = b.uid JOIN forum_topics c ON a.tid = c.tid JOIN user_char f ON b.uid = f.uid WHERE f.mainChar = 1 AND a.cid = '.$row->refid)->fetch();
				$a = $this->db->query('SELECT cid FROM forum_topics_comment WHERE tid = "'.$temp->tid.'"')->rowCount();
				if ($temp->cid != 0 and $temp->cid != null){
					pq('#latest-posts')->append('
									<div class="latest-posts-row min-height">
										<div class="latest-posts-row-content min-height"><a href="{path}account/?uid='.$temp->uid.'" class="color-'.strtolower($temp->class).'">'.$temp->name.'</a> added a new post in <a href="{host}/forum/section/thread/?tid='.$temp->tid.'&page='.ceil($a/10).'#comment-'.$temp->cid.'" class="sy-yellow">'.$temp->title.'</a></div>
										<div class="latest-posts-row-title">'.$temp->date.'</div>
									</div>
					');
				}
			}else if($row->type == 1 and $row->refid != 0){
				$temp = $this->db->query('SELECT * FROM media_media WHERE vmid ='.$row->refid)->fetch();
				switch($temp->type){
					case 1 :
						$q = "SELECT * FROM media_screenshot a JOIN user b ON a.user = b.uid JOIN user_char c ON b.uid = c.uid WHERE c.mainChar = 1 AND id =".$temp->id;
						$medium = "screenshot";
						break;
					case 2 :
						$q = "SELECT * FROM media_video a JOIN user b ON a.user = b.uid JOIN user_char c ON b.uid = c.uid WHERE c.mainChar = 1 AND id =".$temp->id;
						$medium = "video";
						break;
					case 3 :
						$q = "SELECT * FROM media_stream a JOIN user b ON a.user = b.uid JOIN user_char c ON b.uid = c.uid WHERE c.mainChar = 1 AND id =".$temp->id;
						$medium = "stream";
						break;
				}
				$query = $this->db->query($q)->fetch();
				pq('#latest-posts')->append('
								<div class="latest-posts-row min-height">
									<div class="latest-posts-row-content min-height"><a href="{path}account/?uid='.$query->uid.'" class="color-'.strtolower($query->class).'">'.$query->name.'</a> added a new '.$medium.' in <a href="{host}/media/show?id='.$temp->vmid.'" class="sy-yellow">'.$query->title.'</a></div>
									<div class="latest-posts-row-title">'.$query->date.'</div>
								</div>
				');
			}
		}
	}
	
	private function loadProgress(){
		foreach($this->db->query('SELECT * FROM progressbar') as $row){
			pq('#raid-progress')->append('
							<div id="progress-box-'.$row->name.'" class="progress-box border">
								<div id="progress_box_title" class="progress-box-title">'.$row->fullname.'</div>
								<div id="progress_box_progressbar" class="progress-box-progressbar centred-margin border border-radius">
									<div id="progress" class="progress float-left border-radius" style="width: '.(178/$row->clearOf*$row->clear).'px;"></div>
									<div id="progress_text" class="progress-text float-left">'.$row->clear.'/'.$row->clearOf.'</div>
								</div>
							</div>
			');
		}
	}
	
	private function isLink($string){
		if(($pos = strpos($string, 'http://')) !== false){
			$text = substr($string, $pos);
			if(strpos(substr($text, strpos($text, ' ')), 'http://') === false){
				$arg = str_replace(substr($text, strpos($text, ' ')), '', $text);
			}else{
				$arg = $text;
			}
			$string = str_replace($arg, '[url='.$arg.']'.$arg.'[/url]', $string);
			return $string;
		}else{
			if(($pos = strpos($string, 'https://')) !== false){
				$text = substr($string, $pos);
				if(strpos(substr($text, strpos($text, ' ')), 'https://') === false){
					$arg = str_replace(substr($text, strpos($text, ' ')), '', $text);
				}else{
					$arg = $text;
				}
				$string = str_replace($arg, '[url='.$arg.']'.$arg.'[/url]', $string);
				return $string;
			}else{
				return $string;
			}
		}
	}
	
	private function loadNewEvents(){
		if ($this->userAgent->isConfirmed()){
			$content .= '
								<div class="sideBar-module-container border-glow min-height box-color">
									<div class="sideBar-module-container-title box-color-no-opa text-bordered">Upcoming events</div>
			';						
			foreach($this->db->query('SELECT * FROM calender_event WHERE timestamp <= '.(time()+60*60*24*7).' AND timestamp >='.(time()-60*60*24).' ORDER BY timestamp') AS $row){
				$content .= '
									<div class="sideBar-newEvents-row">
										<div class="sideBar-newEvents-row-date text-bold"><a href="{path}calendar/event/?date='.$row->date.'">'.$row->date.'</a></div>
										<div class="sideBar-newEvents-row-top">
											<div class="newEvents-img img-shadow float-left" style="background-image: url(\'{path}calendar/event/img/r'.$row->img.'.png\')"></div>
											<div class="newEvents-title float-left">'.$row->title.'</div>
											<div class="newEvents-title float-left">Time: '.$row->time.'</div>
										</div>
										<div class="sideBar-newEvents-row-bottom img-shadow">'.$row->description.'</div>
									</div>
				';
			}
			$content .= '
								</div>
			';
			pq('#new-events')->append($content);
		}
	}
	
	private function loadLatestThreads(){
		if ($this->userAgent->isConfirmed()){
			$content .= '
								<div class="sideBar-module-container border-glow box-color">
									<div class="sideBar-module-container-title box-color-no-opa text-bordered">Latest threads</div>
									<div class="scrollbar latest-threads">
			';						
			foreach ($this->db->query('SELECT a.cid, a.uid, a.tid, a.title, c.class, b.name, a.date, (SELECT COUNT(d.cid) FROM forum_topics_comment d WHERE d.tid=a.tid) AS num FROM forum_topics_comment a RIGHT JOIN (SELECT MAX(c.cid) cid, c.tid FROM forum_topics_comment c GROUP BY c.tid DESC ORDER BY MAX(c.cid) DESC) AS b ON a.cid = b.cid AND a.tid = b.tid RIGHT JOIN user b ON a.uid = b.uid RIGHT JOIN user_char c ON b.uid = c.uid RIGHT JOIN forum_topics d ON a.tid = d.tid RIGHT JOIN forum_section_topics e ON d.gtid = e.gtid WHERE c.mainChar = 1 AND e.readpermission <= '.$this->userAgent->rank.' LIMIT 20') as $row){
				$content .= '
					<div class="latest-posts-row min-height" style="margin: 0px auto;">
						<div class="latest-posts-row-content min-height"><a href="{path}account/?uid='.$row->uid.'" class="color-'.strtolower($row->class).'">'.$row->name.'</a> added a new post in <a href="{host}/forum/section/thread/?tid='.$row->tid.'&page='.ceil(($row->num-1)/10).'#comment-'.$row->cid.'" class="sy-yellow">'.$row->title.'</a></div>
						<div class="latest-posts-row-title">'.$row->date.'</div>
					</div>
				';
			}
			$content .= '
								</div>
								</div>
			';
			pq('#new-threads')->append($content);
		}
	}
	
	private function loadLFM(){
		if (!$this->userAgent->isLoggedIn() or !$this->userAgent->isConfirmed()){
			pq('#sideBar-lfm')->append('
						<div class="sideBar-module-container border-glow min-height box-color">
							<div class="sideBar-module-container-title box-color-no-opa text-bordered">Looking For Member</div>
							<div class="sideBar-module-container-content min-height padding-5">
								<div class="lfm-row border-bottom">
									<div id="lfm-warrior" class="lfm-row-content float-left color-warrior">Warrior</div>
									<div id="lfm-warrior-prio" class="lfm-row-prio float-left">Middle</div>
								</div>
								<div class="lfm-row border-bottom">
									<div id="lfm-rogue" class="lfm-row-content float-left color-rogue">Rogue</div>
									<div id="lfm-rogue-prio" class="lfm-row-prio float-left">Middle</div>
								</div>
								<div class="lfm-row border-bottom">
									<div id="lfm-priest" class="lfm-row-content float-left color-priest">Priest</div>
									<div id="lfm-priest-prio" class="lfm-row-prio float-left">Middle</div>
								</div>
								<div class="lfm-row border-bottom">
									<div id="lfm-hunter" class="lfm-row-content float-left color-hunter">Hunter</div>
									<div id="lfm-hunter-prio" class="lfm-row-prio float-left">Middle</div>
								</div>
								<div class="lfm-row border-bottom">
									<div id="lfm-druid" class="lfm-row-content float-left color-druid">Druid</div>
									<div id="lfm-druid-prio" class="lfm-row-prio float-left">Middle</div>
								</div>
								<div class="lfm-row border-bottom">
									<div id="lfm-mage" class="lfm-row-content float-left color-mage">Mage</div>
									<div id="lfm-mage-prio" class="lfm-row-prio float-left">Middle</div>
								</div>
								<div class="lfm-row border-bottom">
									<div id="lfm-warlock" class="lfm-row-content float-left color-warlock">Warlock</div>
									<div id="lfm-warlock-prio" class="lfm-row-prio float-left">Middle</div>
								</div>
								<div class="lfm-row">
									<div id="lfm-paladin" class="lfm-row-content float-left color-paladin">Paladin</div>
									<div id="lfm-paladin-prio" class="lfm-row-prio float-left">Middle</div>
								</div>
								<div id="lfm-apply-button" class="lfm-apply-button button box-color border border-radius"><a href="#">Apply!</a></div>
							</div>
						</div>
			');
		}
	}
	
	private function loadShoutbox(){
		if ($this->userAgent->isConfirmed() && $this->userAgent->rank!=0){
			foreach($this->db->query('SELECT user.uid, user.name, text, date, user_char.class FROM shoutbox JOIN user ON shoutbox.uid = user.uid JOIN user_char ON user.uid = user_char.uid WHERE mainChar = 1 ORDER BY gid DESC LIMIT 15') AS $row){
				pq('#shoutbox')->append('
									<div class="shoutbox-row min-height border">
										<div class="shoutbox-row-title box-color-no-opa border-bottom"><a href="{path}account/?uid='.$row->uid.'" class="color-'.strtolower($row->class).'">'.$row->name.'</a> says on <span class="sy-yellow">'.$row->date.'</span>:</div>
										<div class="shoutbox-row-content min-height box-color padding-5">'.$this->parser->parse($this->isLink($row->text))->getAsHtml().'</div>
									</div>
				');
			}
		}else{
			pq('#shoutbox')->append('Log in to view posts!');
		}
	}
	
	public function setLFMPrio($class, $prio){
		pq('#lfm-'.$class.'-prio')->replaceWith('<div id="lfm-'.$class.'-prio" class="lfm-row-prio float-left prio-'.$prio.'">'.$prio.'</div>');
	}
	
	private function fillComponents(){
		// LFM Prio
		$this->loadLFM();
		$this->setLFMPrio('warrior', 'Medium');
		$this->setLFMPrio('rogue', 'Low');
		$this->setLFMPrio('priest', 'Low');
		$this->setLFMPrio('hunter', 'Low');
		$this->setLFMPrio('druid', 'Low');
		$this->setLFMPrio('mage', 'Low');
		$this->setLFMPrio('warlock', 'Low');
		$this->setLFMPrio('paladin', 'Low');
		
		// Alteration if logged in
		if($this->userAgent->isLoggedIn())
			$this->loggedIn();
		
		// Shoutbox
		$this->loadShoutbox();
		
		//New Events
		$this->loadNewEvents();
		$this->loadLatestThreads();
		
		// Raid Progress
		$this->loadProgress();
		
		// Latest Posts
		$this->loadLatestPosts();
		
		// Fill Content
		$this->buildContent();
		
		// Add Css
		$this->addCss();
	}
	
	private function loggedIn(){
		// Navbar Apply -> Account
		pq('#navbar-apply-account')->replaceWith('<a id="navbar-apply-account" href="{host}/account/?uid='.$this->userAgent->uid.'">Account</a>');
		
		// LFM Apply Button
		pq('#lfm-apply-button')->replaceWith('');
		
		// Useful Links TS3 Link
		pq('#ts3')->replaceWith('<div id="ts3" class="useful-links-row border-bottom"><a href="ts3server://46.105.147.204?port=9988&password=graby">Teamspeak3-Server</a></div>');
		
		// Shoutbox form
		pq('#sideBar-shoutbox-content')->append('
							<div id="shoutbox-form" class="shoutbox-form">
								<form action="{path}Modules/ShoutboxPost.php" method="post">
									<input type="text" name="text" class="input-text border box-color shoutbox-text" />
									<input type="hidden" name="path" value="{location}" />
									<input type="hidden" name="uid" value="'.$this->userAgent->uid.'" />
									<input type="submit" value="Send" class="input-submit border box-color shoutbox-submit" />
								</form>
							</div>
		');
		
		// User Menu
		if(file_exists(ROOT.'/../Database/img/User/'.$this->userAgent->uid.'.png')){ $pic = $this->userAgent->uid; }else{ $pic = 'default'; }
		pq('#user-menu')->replaceWith('
							<div id="user-menu" class="user-menu padding-5">
								<div class="user-menu-label color-'.strtolower($this->userAgent->mainChar()->class).'">'.$this->userAgent->name.'</div>
								<div class="user-menu-pic-box">
									<div class="user-menu-pic centred-margin border img-shadow border-radius box-color" style="background-image: url(\'{path}Database/img/User/'.$pic.'.png\');"></div>
								</div>
								<div class="user-menu-info">
									<div class="user-menu-info-row">
										<div class="user-menu-info-row-title float-left">Main:</div>
										<div class="user-menu-info-row-content float-left">'.$this->userAgent->mainChar()->charName.'</div>
									</div>
									<div class="user-menu-info-row">
										<div class="user-menu-info-row-title float-left">Class:</div>
										<div class="user-menu-info-row-content float-left">'.$this->userAgent->mainChar()->class.'</div>
									</div>
									<div class="user-menu-info-row">
										<div class="user-menu-info-row-title float-left">Rank:</div>
										<div class="user-menu-info-row-content float-left">'.$this->userAgent->rankName.'</div>
									</div>
								</div>
								<div class="button box-color border-radius border centred-margin"><a href="?logout&location={location}">Logout</a></div>
							</div>
		');
		
	}
	
	public function run(){
		$this->fillComponents();
		$this->addParams();
		$this->setLinks();
		print $this->doc;
	}
	
	public function __construct($db, $theme = '', $userAgent, $parser, $file){
		$this->db = $db;
		$this->doc = $this->newDocumentFileHTML(ROOT.'/../Themes/'.$theme.'/index.html');
		$this->userAgent = $userAgent;
		$this->parser = $parser;
		$this->file = $file;
	}
}
?>
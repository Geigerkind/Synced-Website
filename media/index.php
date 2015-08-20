<?php
require_once __DIR__ .'/../Init.php';

class BSite extends Site{
	
	private function goHome(){
		if(!$this->userAgent->isConfirmed()){
			header('Location: ../');
			exit();
		}
	}
	
	private function leContent(){
		for($i = 1; $i <= 3; $i++){
			if($_GET["page"] == $i){
				$limit[$i] = '';
				break;
			}else{
				$limit[$i] = 'LIMIT 4';
			}
		}
		$stream = $this->db->query('SELECT * FROM media_stream a JOIN user b ON a.user = b.uid WHERE b.confirmed = 1 '.$limit[1]);
		$video = $this->db->query('SELECT * FROM media_video a JOIN user b ON a.user = b.uid WHERE b.confirmed = 1 '.$limit[2]);
		$pic = $this->db->query('SELECT * FROM media_screenshot a JOIN user b ON a.user = b.uid WHERE b.confirmed = 1 '.$limit[3]);
		
		$content .= '
			<div class="media-box border border-radius box-color padding-10">
		';
		if($_GET["page"] == 1 OR !isset($_GET["page"]) OR empty($_GET["page"])){
			$content .= '
					<div class="media-box-row min-height">
						<div class="media-box-row-title"><a href="{host}/media/?page=1">Streams ('.$this->db->query('SELECT * FROM media_stream a JOIN user b ON a.user = b.uid WHERE b.confirmed = 1 ')->rowCount().')</a></div>
						<div class="media-box-row-content min-height">
			';
			foreach($stream AS $val){
				$content .= '
						<a href="{host}/media/show/?id='.$this->db->query('SELECT vmid FROM media_media WHERE type= 3 AND id ='.$val->id)->fetch()->vmid.'">
							<div class="media-box-item min-height float-left border-radius padding-10">
								<div class="media-box-item-pic border border-radius img-shadow box-color" style="background-image: url(\''.$val->tempPic.'\');"></div>
								<div class="media-box-item-row">Status: '.$val->tempStatus.'</div>
								<div class="media-box-item-row">Channel: '.$val->link.'</div>
								<div class="media-box-item-row">Viewer: '.$val->tempViewer.'</div>
								<div class="media-box-item-row">Game: '.$this->shortenString(18, $val->tempGame).'</div>
								<div class="media-box-item-row">Views: '.$val->tempViews.'</div>
								<div class="media-box-item-row">Follower: '.$val->tempFollower.'</div>
								<div class="media-box-item-row">Uploaded by: '.$val->name.'</div>
							</div>
						</a>
				';
			}
			$content .= '
						</div>
					</div>
			';
		}
		if($_GET["page"] == 2 OR !isset($_GET["page"]) OR empty($_GET["page"])){
			$content .= '
					<div class="media-box-row min-height">
						<div class="media-box-row-title"><a href="{host}/media/?page=2">Videos ('.$this->db->query('SELECT * FROM media_video a JOIN user b ON a.user = b.uid WHERE b.confirmed = 1')->rowCount().')</a></div>
						<div class="media-box-row-content min-height">
			';
			foreach($video AS $val){
				$content .= '
						<a href="{host}/media/show/?id='.$this->db->query('SELECT vmid FROM media_media WHERE type= 2 AND id ='.$val->id)->fetch()->vmid.'">
							<div class="media-box-item min-height float-left border-radius padding-10">
								<div class="media-box-item-pic border border-radius img-shadow box-color" style="background-image: url(\'http://img.youtube.com/vi/'.$val->link.'/0.jpg\');"></div>
								<div class="media-box-item-row">'.$val->title.'</div>
								<div class="media-box-item-row">Uploaded by: '.$val->name.'</div>
							</div>
						</a>
				';
			}
			$content .= '
						</div>
					</div>
			';
		}
		if($_GET["page"] == 3 OR !isset($_GET["page"]) OR empty($_GET["page"])){
			$content .= '
					<div class="media-box-row min-height">
						<div class="media-box-row-title"><a href="{host}/media/?page=3">Screenshots ('.$this->db->query('SELECT * FROM media_screenshot a JOIN user b ON a.user = b.uid WHERE b.confirmed = 1')->rowCount().')</a></div>
						<div class="media-box-row-content min-height">
			';
			foreach($pic AS $val){
				$content .= '
						<a href="{host}/media/show/?id='.$this->db->query('SELECT vmid FROM media_media WHERE type= 1 AND id ='.$val->id)->fetch()->vmid.'">
							<div class="media-box-item min-height float-left border-radius padding-10">
								<div class="media-box-item-pic border border-radius img-shadow box-color" style="background-image: url(\'{path}Database/img/media/'.$val->link.'_small.png\');"></div>
								<div class="media-box-item-row">'.$val->title.'</div>
								<div class="media-box-item-row">Uploaded by: '.$val->name.'</div>
							</div>
						</a>
				';
			}
			$content .= '
						</div>
					</div>
			';
		}
		$content .= '
				<div class="media-navbar padding-10">
					<div id="add" class="button border border-radius box-color"><a href="{host}/media/newMedia/">Add Media</a></div>
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
		$this->goHome();
	}
}
$site = new BSite($db, 'Default', $userAgent, $parser, __DIR__);
$site->setContentName('Media');
$site->run();
?>
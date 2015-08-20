<?php
/*
* Synced Class File
*
* This file provides the Class information
* for a simple UserAgent.
*
* @requires Database Class
*
* @author Albea <https://bitbucket.org/Albea>
* @license <https://synced-kronos.net/license>
* @package Helper/User/LogOut
* @category User
*
* @version 0.1.0 (Synced)
*/
session_start();

class LogOut{
	
	private function goHome(){
		if(isset($_GET['location']) AND !empty($_GET['location']))
			header('Location: '.$_GET['location']);
		else
			header('Location: /');
		exit();
	}
	
	/*
	* Destroys the Cookie and refreshes the page.
	*/
	public function __construct(){
		unset($_COOKIE['user']);
		setCookie('user', null, -1);
		$_SESSION['user'] = null;
		session_destroy();
		$this->goHome();
	}
}
new LogOut;
?>
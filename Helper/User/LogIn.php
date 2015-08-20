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
* @package Helper/User/LogIn
* @category User
*
* @version 0.1.0 (Synced)
*/
session_start();
require_once __DIR__ . '/../../Database/Mysql.php';

class LogIn{
	/*
	* Simple logIn script.
	*/
	public function __construct($db, $name = '', $pass = '', $path){
		if($r = $db->query('SELECT * FROM user WHERE name = "'.$name.'"')->fetch()){
			$pass = md5($pass);
			if($r->pass == $pass){
				$_SESSION['user'] = $r->uid;
				setCookie('user', $r->uid, time() + 60*60*24*7);
				header('Location: '.$path);
			}else{
				// Exception here
				header('Location: '.$path);
			}
		}else{
			// Exception here
			header('Location: '.$path);
		}
	}
}
$keyData = new KeyData();
$db = new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
new LogIn($db, $_POST["name"], $_POST["pass"], $_POST["hidden-values"]);
?>
<?php
/*
* Synced Class File
*
* This file provides the Class information
* for an Database.
*
* @requires PDO Class
*
* @author Albea <https://bitbucket.org/Albea>
* @license <https://synced-kronos.net/license>
* @credits <https://github.com/BitCoding/BitCore-PHP/blob/Breadcrumb/Database/BMysql.php>
* @package Database/Mysql
* @category Database
*
* @version 0.1.0 (Synced)
*/

require_once('Database.php');

class KeyData{
	public $host = '';
	public $user = '';
	public $pass = '';
	public $db = '';
	
	public function __construct(){
		if($_SERVER[HTTP_HOST] == '127.0.0.1'){
			$this->host = '127.0.0.1';
			$this->user = 'root';
			$this->pass = '';
			$this->db = 'sneaky';
		}else{
			$this->host = '****';
			$this->user = '****';
			$this->pass = '****';
			$this->db = '****';
		}
	}
}

class Mysql extends Database {
	/**
	* Construct Simple Mysql Connection
	*
	* @var string $host Mysql Host
	* @var string $username Mysql Username
	* @var string $password Mysql Password
	* @var string $db Mysql DB
	* @var $port $port Port
	*
	* @see parent::__construct()
	*/
	function __construct($host, $username, $password, $db, $port = 3306, $utf8 = false, $driver_options = array()) {
		if ($host == '')
			//throw new DatabaseException('no_host');
			die('No Host');
		if (!$username)
			//throw new DatabaseException('no_user');
			die('No User');
		if (!$db)
			//throw new DatabaseException('no_db');
			die('No DB');
		if (!$port || !is_numeric($port) || $port < 1 || $port > 65535)
			//throw new DatabaseException('wrong_port');
			die('Wrong Port');
		parent::__construct('mysql:dbname=' . $db . ';host=' . $host . ';port=' . $port . (($utf8) ? ';charset=utf8' : ''), $username, $password, $driver_options);
	}
}
?>
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
* @credits <https://github.com/BitCoding/BitCore-PHP/blob/Breadcrumb/Database/BDatabase.php>
* @package Database/Database
* @category Database
*
* @version 0.1.0 (Synced)
*/

class Database extends PDO {
	/**
	* Simple check that the database connection has been made
	* @var bool $_connect
	*/
	private $_connect = false;
	
	/**
	* PDO Connection String
	* @var string $_dsn
	*/
	private $_dsn = '';
	
	/**
	* PDO User
	* @var string $_username
	*/
	private $_username = '';
	
	/**
	* PDO Password
	* @var string $_password
	*/
	private $_password = '';
	
	/**
	* PDO Driver
	* @var mixed $_driver_options
	*/
	private $_driver_options = array();
	
	/**
	* Construct Simple PDO Wrapper Construct
	*
	* @var string $dsn PDO Connection String
	* @var string $username PDO User
	* @var string $password PDO Password
	* @var mixed $driver_options
	*/
	public function __construct($dsn, $username = '', $password = '', $driver_options = array()) {
		$this->_dsn = $dsn; 
		$this->_username = $username;
		$this->_password = $password;
		$this->_driver_options = $driver_options;
	}
	
	/**
	* Construct PDO
	*
	* @var string $dsn PDO Connection String
	* @var string $username PDO User
	* @var string $password PDO Password
	* @var mixed $driver_options
	*
	* @see parent::__construct()
	*/
	public function __connect() {
		try {
			parent::__construct($this->_dsn, $this->_username, $this->_password, $this->_driver_options);
			$this->_connect = true;
			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			//throw new DatabaseException('no_host');
			die('Maintenance - Will be up soon again!');
		}
	}
	
	/**
	* @see parent::beginTransaction()
	*/
	public function beginTransaction() {
		if (!$this->_connect)
			$this->__connect();
		return parent::beginTransaction();
	}
	
	/**
	* @see parent::errorCode()
	*/
	public function errorCode() {
		if (!$this->_connect)
			$this->__connect();
		return parent::errorCode();
	}
	
	/**
	* @see parent::errorInfo()
	*/
	public function errorInfo() {
		if (!$this->_connect)
			$this->__connect();
		return parent::errorInfo();
	}
	
	/**
	* @see parent::exec()
	*/
	public function exec($statement) {
		if (!$this->_connect)
			$this->__connect();
		return parent::exec($statement);
	}
	
	/**
	* @see parent::prepare()
	*/
	public function prepare($statement, $driver_options = array()) {
		if (!$this->_connect)
			$this->__connect();
		return parent::prepare($statement, $driver_options);
	}
	
	/**
	* @see parent::query()
	*/
	public function query($statement) {
		if (!$this->_connect)
			$this->__connect();
		return parent::query($statement);
	}
	
	/**
	* @see parent::quote()
	*/
	public function quote($string, $parameter_type = PDO::PARAM_STR) {
		if (!$this->_connect)
			$this->__connect();
		return parent::quote($string, $parameter_type);
	}

}
?>
<?php
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

session_start();
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding("UTF-8");

require 'Database/Mysql.php';

require 'Helper/UTF8.php';
require 'Helper/User/UserAgent.php';

require 'External/phpQuery/phpQuery.php';
require 'External/jBBCode/JBBCode/Parser.php';

require 'Modules/Site.php';

if(isset($_GET['logout']))
	include 'Helper/User/LogOut.php';

// Initialising variables
$keyData = new KeyData();
$db = new Mysql($keyData->host, $keyData->user, $keyData->pass, $keyData->db, 3306, false, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$userAgent = new userAgent($db);
$parser = new JBBCode\Parser();
$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
?>
<?php
/*
* Synced Class File
*
* This file provides the Class information
* for the SideBarModule.
*
* @author Albea <https://bitbucket.org/Albea>
* @license <https://synced-kronos.net/license>
* @package Helper/UTF8
* @category Helper
*
* @version 0.1.0 (Synced)
*/

class UTF8 {
	/**
	* @link http://php.net/manual/de/function.chr.php
	*/
	static function HtmlChar($u) {
		return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
	}
}
?>
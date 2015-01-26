<?php
	defined('SQL_USER') or define('SQL_USER', "tinkerman");
	defined('SQL_PASS') or define('SQL_PASS', "tinkathon!1909" );
	defined('SQL_HOST') or define('SQL_HOST', "dbinst.cavyzyys1fuy.us-west-2.rds.amazonaws.com:3306");
	defined('SQL_PORT') or define('SQL_PORT', '3306');
	defined('SQL_DB')   or define('SQL_DB', 'SQLIDEMO_DB');

	define('SCRIPT_ROOT', 'http://localhost/SQLI');
	define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/SQLI');

	/*Paths*/
	defined('TEMPLATES_PATH') or define('TEMPLATES_PATH', DOCUMENT_ROOT . '/app/templates');
	defined('LIB_PATH') or define('LIB_PATH', DOCUMENT_ROOT . '/app/lib');
	defined('VENDOR_PATH') or define('VENDOR_PATH', SCRIPT_ROOT . '/vendor/');

	$scope = new stdClass(); 

	require_once LIB_PATH . '/sqli.php'; 
	require_once LIB_PATH .'/mysqli.php'; 
	require_once LIB_PATH . '/thread.php'; 	
	require_once LIB_PATH . '/post.php'; 	
	require_once LIB_PATH . '/utils.php'; 	

	$sqli = new My_SQLI( SQL_HOST  , SQL_USER ,  SQL_PASS , SQL_DB ); 
?>
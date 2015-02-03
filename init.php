<?php
	defined('SQL_USER') or define('SQL_USER', "root");
	defined('SQL_PASS') or define('SQL_PASS', "" );
	defined('SQL_HOST') or define('SQL_HOST', "localhost");
	defined('SQL_PORT') or define('SQL_PORT', '3306');
	defined('SQL_DB')   or define('SQL_DB', 'sqli_demo');

	define('SCRIPT_ROOT', 'http://localhost/osqli-demo');
	define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/osqli-demo');

	/*Paths*/
	defined('TEMPLATES_PATH') or define('TEMPLATES_PATH', DOCUMENT_ROOT . '/app/templates');
	defined('LIB_PATH') or define('LIB_PATH', DOCUMENT_ROOT . '/app/lib');
	defined('VENDOR_PATH') or define('VENDOR_PATH', SCRIPT_ROOT . '/vendor/');

	defined('PAGE_SIZE') or define('PAGE_SIZE', 25);


	$scope = new stdClass(); 

	require_once LIB_PATH . '/sqli.php'; 
	require_once LIB_PATH .'/myosqli.php'; 
	require_once LIB_PATH . '/thread.php'; 	
	require_once LIB_PATH . '/post.php'; 	
	require_once LIB_PATH . '/utils.php'; 	

	$sqli = new My_OSQLI( SQL_HOST  , SQL_USER ,  SQL_PASS , SQL_DB ); 
?>
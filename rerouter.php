<?php
	require_once 'router.php'; 

	$router = new Router();

	/*From Routers Perspective.*/ 
	$router->add( '/^\/?(404)\/?$/' , './public/404.php' ); 

	$router->add( '/^\/?(index)?$/' , './app/index.php' ); 

	$router->add( '/^\/?vendor\/([^\.]+)$/' ,  function($url){
		$matches = array(); 
		preg_match('/^\/?vendor\/([^\.]+)$/', $url , $matches); 
		return './vendor/' . trim( $matches[1] , '/') .'.php'; 
	}); 

	$router->add( '/^\/?([^\.]+)$/' ,  
		function($url){ 
			$matches = array(); 
			preg_match('/^\/?([^\.]+)$/', $url , $matches); 
			return './app/' . trim( $matches[1] , '/') .'.php'; 
		} 
	); 


	
	$router->route();
?>
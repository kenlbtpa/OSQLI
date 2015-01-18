<?php

function getParam($name, $default = null){
	if( isset($_GET[$name]) )
	{
		return $_GET[$name]; 
	}
	else{
		if( $default !== null ) return $default; 
		throw new Exception("Missing Parameter $name"); 
	}
}

function postParam($name, $default = null){
	if( isset($_POST[$name]) )
	{
		return $_POST[$name]; 
	}
	else{
		if( $default !== null ) return $default; 
		throw new Exception("Missing Parameter $name"); 
	}
}

?>
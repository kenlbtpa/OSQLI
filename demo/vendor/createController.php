<?php

session_start(); 

try{

	$create = getParam('create', null); 
	if($create === null)throw new Exception("Improper form. Please contact admin.");

	if($create == 'thread'){
		$threadBuild = $_POST;
		$thread = $sqli->make("Thread", $threadBuild); // this creates a thread object using the parameters provided. 
		$res = $sqli->save($thread); // this saves the thread object. Updates on no match. 
	}
	if($create =='post'){
		$postBuild = $_POST; 
		$postBuild['thread_id'] = getParam('thread'); 
		$post = $sqli->make("Post", $postBuild); 
		$res = $sqli->save($post); 
	}
	header("Location: " . $_SERVER['HTTP_REFERER']); 
}
catch(Exception $e){
	$_SESSION['error'] = $e->getMessage(); 
	die($e->getMessage());
	// header("Location: index.php"); 	
}

?>
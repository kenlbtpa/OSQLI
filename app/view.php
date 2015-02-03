<?php 

	try{
		$id = getParam('id' , null); 
		if($id === null){ throw new Exception("You need to select a thread."); }
		$thread = $sqli->find('Thread' , ' where id = ? ' , [ $id ] ); 
		$scope->title = $thread->title; 
	}
	catch(Exception $e){
		die($e->getMessage()); 
	}

?>

<?php
	$content_path = "_view.php";
	require_once TEMPLATES_PATH . '/wrapper.php'; 
?>

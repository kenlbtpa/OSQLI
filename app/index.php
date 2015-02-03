<?php 
	$scope->title = 'SQLI DEMO INDEX'; 

	try{
		$page = getParam('page', 1); 
		$threads = $sqli->where('Thread' , 'limit ? , ?' , [ ( ( $page - 1 ) * PAGE_SIZE ) , PAGE_SIZE ]); 

		$searchCount = $sqli->count('Thread'); 

		$totalPages = max( ceil( $searchCount / PAGE_SIZE ) , 1); 

		$threadIds = array_map( function($thread){ return $thread->id; } , $threads ); 
		$postCounts = $sqli->getThreadReplies( $threadIds ); 
		$posts = $sqli->getLastThreadPost( $threadIds );
	}
	catch(Exception $e){
		die($e->getMessage()); 
	}
	$content_path = "_index.php"
?>
<?php
	require_once TEMPLATES_PATH . '/wrapper.php'; 
?>

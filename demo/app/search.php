<?php 
	$scope->title = 'SQLI DEMO SEARCH RESULTS'; 

	try{
		$query = getParam('query' , null); 
		$page = getParam('page', 1); 
		$s_time = microtime(1);
		$threads = $sqli->searchThreads($query); 
		$e_time = microtime(1); 

		$searchCount = count($threads); 

		$threads = array_splice($threads, ( ( $page - 1 ) * PAGE_SIZE )  , PAGE_SIZE ); 

		$totalPages = max( ceil( $searchCount / PAGE_SIZE ) , 1); 

		$threadIds = array_map( function($thread){ return $thread->id; } , $threads ); 
		$postCounts = $sqli->getThreadReplies( $threadIds ); 
		$posts = $sqli->getLastThreadPost( $threadIds );
	}
	catch(Exception $e){
		die($e->getMessage()); 
	}

?>
	<?php $content_path = "_search.php"; ?>

<?php
	require_once TEMPLATES_PATH . '/wrapper.php'; 
?>

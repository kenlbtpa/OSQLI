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

		$totalPages = ceil( $searchCount / PAGE_SIZE ); 

		$threadIds = array_map( function($thread){ return $thread->id; } , $threads ); 
		$postCounts = $sqli->getThreadReplies( $threadIds ); 
		$posts = $sqli->getLastThreadPost( $threadIds );
	}
	catch(Exception $e){
		die($e->getMessage()); 
	}

?>
	<?php ob_start(); ?>
    <div class='container'>
    	<div class='container-fluid forumwrap'>
    		<div class='contenthead'>
    			<h1>There are <?=$searchCount?> found for <strong>Search: <?=$query?></strong>. </h1>
    			<h6>&nbsp;&nbsp;<?=$searchCount?> found in <?= number_format( ($e_time - $s_time)/1000 , 6 ) ?> seconds</h6>
    		</div>
		  	<div class='contentwrap' >
	  			<table class='table'>
	  			<?php foreach($threads as $key => $thread): ?>	  				
	  				<tr >
	  					<td >
	  						<h4><a href='view?id=<?=$thread->id?>'><?=$thread->title?></a></h4>
	  						<span class='subtext'>Started by <?=$thread->creator_name?> , <?=date("F d Y h:i a" , strtotime($thread->created_date));?></span>
	  					</td>
	  					<td>
	  						<!-- Post Count -->
	  						<?= $postCounts[$key]['total']; ?>
	  					</td>
	  					<td>
	  						<!-- Last Poster -->
	  						<?php if(isset($posts[$key])): ?>
	  							<?= $posts[$key]->creator_name; ?>	
	  						<?php else: ?>
	  							No Replies Yet.
	  						<?php endif; ?>
	  					</td>
	  				</tr>
		  		<?php endforeach; ?>
	  			</table>
		  	</div>

		  	<div class='contentwrap'>
				<div class='row' >
					<div style='position:relative;left:48%;right:56%;' >
						<a href='<?=SCRIPT_ROOT?>/search?query=<?=$query?>&page=1' class='styleless'> <span class='col-md-1 pageanchors'> &lt;&lt; </span> </a>
						<a href='<?=SCRIPT_ROOT?>/search?query=<?=$query?>&page=<?=max(1,$page-1)?>' class='styleless'> <span class='col-md-1 pageanchors'> &lt; </span> </a>
						<?php for($i = max(1, $page - 3) ; $i < min( $totalPages + 1, $page + 4); $i++ ): ?>
							<a href='<?=SCRIPT_ROOT?>/search?query=<?=$query?>&page=<?=$i?>' class='styleless'>
								<span class='col-md-1 pageanchors <?php if($i == $page ) echo "active"; ?> '> <?=$i?> </span>
							</a>
						<?php endfor; ?>
						<a href='<?=SCRIPT_ROOT?>/search?query=<?=$query?>&page=<?=min($totalPages,$page+1)?>' class='styleless'> <span class='col-md-1 pageanchors'> &gt; </span> </a>
						<a href='<?=SCRIPT_ROOT?>/search?query=<?=$query?>&page=<?=$totalPages?>' class='styleless'> <span class='col-md-1 pageanchors'> &gt;&gt; </span> </a>
					</div>
				</div>  
		  	</div>

    	</div>
    </div>
    <?php $scope->content = ob_get_contents(); ob_end_clean(); ?>

<?php
	require_once TEMPLATES_PATH . '/wrapper.php'; 
?>

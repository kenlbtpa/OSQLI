<?php 
	$scope->title = 'SQLI DEMO INDEX'; 
?>
	<?php ob_start(); ?>
    <div class='container'>
    	<div class='container-fluid forumwrap'>
		  	<div class='contentwrap' >
	  			<!-- Threads Goes Here. -->
	  			<?php 
	  				$threads = $sqli->where( 'Thread' ); 
	  				$threadCount = count($threads); 
	  				$postCounts = $sqli->query("select t.id , count(p.id) total from post p right join thread t on p.tid = t.id group by t.id" , null , MYSQLI_ASSOC ); 

	  				$quotes = ""; $tidArray = []; 
	  				foreach($threads as $key => $thread){ $quotes .= '?'; $tidArray[] = $thread->id; if($key < count($threads)-1){ $quotes .= " , "; } }
	  				$posts = $sqli->where('Post', "inner join (select post.* from post inner join thread on post.tid = thread.id order by post.created_date desc ) as res on post.id = res.id group by post.tid; " );
  				?>
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
				<div class='row outform'>
					<!-- Create Thread Form Goes Here. -->
					<form class='form-group' action='<?=VENDOR_PATH?>/createController?create=thread' method='post'>
						<label>Your name:</label><input class='form-control' placeholder='Adam Smith' name='creator_name' />
						<label>Thread Title</label><input class='form-control' placeholder='Thread Title' name='title'  />
						<button class='form-control btn btn-success'>Submit</button>
					</form>
				</div>				
			</div> 
    	</div>
    </div>	
    <?php $scope->content = ob_get_contents(); ob_end_clean(); ?>

<?php
	require_once TEMPLATES_PATH . '/wrapper.php'; 
?>

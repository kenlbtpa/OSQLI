<?php 
	$scope->title = 'SQLI DEMO SEARCH RESULTS'; 

	try{
		$query = getParam('query' , null); 
		$sqli->searchForum($query); 
		die();
	}
	catch(Exception $e){
		die($e->getMessage()); 
	}

?>
	<?php ob_start(); ?>
    <div class='container'>
    	<div class='container-fluid forumwrap'>
    		<div class='contenthead'>
    			<h1><?=$thread->title?></h1>
    			<h6>Started by <?=$thread->creator_name?> , <?=date("F d Y h:i a" , strtotime($thread->created_date));?></h6>
    		</div>
		  	<div class='contentwrap' >
	  			<!-- Posts Goes Here. -->
	  			<?php $posts = $sqli->where('Post', 'where tid = ?' , [$thread->id] ) ?>
	  			<?php if( count($posts) == 0 ): ?>
			  		<div class='row post'>
			  			<h2>There are no posts in this thread.</h2>
			  		</div>					
		  		<?php endif; ?>
	  			<?php foreach($posts as $key => $post): ?>
			  		<div class='row post'>
			  			<div class='post-head col-md-12'>			
			  				<div class='col-md-9'>  				
			  				<?= $post->creator_name; ?>
			  				</div>
			  				<div class='col-md-3' align='right' >
			  					 <?=date("F d Y h:i a" , strtotime($post->created_date));?>
		  					</div>
			  			</div>
			  			<div class='post-content'>
			  				<p>
			  					<?=$post->content;?>
			  				</p>
			  			</div>
			  		</div>
		  		<?php endforeach; ?>
		  	</div>
		  	<div class='contentwrap'>
				<div class='row outform'>
					<!-- Create Thread Form Goes Here. -->
					<form class='form-group' action='<?=ASSETS_PATH?>/createController.php?create=post&thread=<?=$thread->id?>' method='post'>
						<label>Your name:</label><input class='form-control' placeholder='Adam Smith' name='creator_name' />
						<label>Content</label><textarea class='form-control' placeholder='' name='content' rows='10' /></textarea>
						<button class='form-control btn btn-success'>Submit</button>
					</form>
				</div>		
				<div class='row'>
					<span class='col-md-push-11 col-md-1'>
						<a href='<?=SCRIPT_ROOT?>/index.php' class='styleless'>Back</a>
					</span>
				</div>  		
			</div> 
    	</div>
    </div>
    <?php $scope->content = ob_get_contents(); ob_end_clean(); ?>

<?php
	require_once TEMPLATES_PATH . '/wrapper.php'; 
?>

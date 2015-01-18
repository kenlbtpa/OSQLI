<?php
	require_once 'sqli.php'; 
	require_once 'thread.php'; 
	require_once 'post.php'; 
	require_once __DIR__.'/utils.php'; 

	$sqli = new SQLI('127.0.0.1', 'root', '' , 'sqli_demo'); // You'll want to use a config file so you don't need reinstantiate every single time. This is for demo. 
	
	try{
		$id = getParam('id' , null); 
		if($id === null){ throw new Exception("You need to select a thread."); }
		$thread = $sqli->get('Thread', $id); 
	}
	catch(Exception $e){
		die($e->getMessage()); 
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$thread->title;?></title>

 	<link rel="stylesheet" type="text/css" href="bootstrap.min.css">

	<!-- <link rel="stylesheet" type="text/css" href="font-awesome.min.css"> -->
	<link rel="stylesheet" type="text/css" href="site.css">

  </head>
  <body>

    <div class="navbar-wrapper">
        <nav class="navbar navbar-inverse navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              </button>
              <a class="navbar-brand" href="#"><?=$thread->title?></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
              </ul>
		      <form class="navbar-form navbar-right col-md-pull-2" role="search">
		        <div class="form-group">
		          <input type="text" class="form-control" placeholder="Search Threads">
		        </div>
		      </form>
            </div>
          </div>
        </nav>
    </div>

    <div class='container'>
    	<div class='container-fluid forumwrap'>
    		<div class='contenthead'>
    			<h1><?=$thread->title?></h1>
    			<h6>Started by <?=$thread->creator_name?> , <?=date("F d Y h:i a" , strtotime($thread->created_date));?></h6>
    		</div>
		  	<div class='contentwrap' >
	  			<!-- Posts Goes Here. -->
	  			<?php $posts = $sqli->where('Post', 'where tid = ?' , [$thread->id] ) ?>
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
					<form class='form-group' action='createController.php?create=post&thread=<?=$thread->id?>' method='post'>
						<label>Your name:</label><input class='form-control' placeholder='Adam Smith' name='creator_name' />
						<label>Content</label><textarea class='form-control' placeholder='' name='content' rows='10' /></textarea>
						<button class='form-control btn btn-success'>Submit</button>
					</form>
				</div>		
				<div class='row'>
					<span class='col-md-push-11 col-md-1'>
						<a href='index.php' class='styleless'>Back</a>
					</span>
				</div>  		
			</div> 
    	</div>
    </div>
  </body>
</html>

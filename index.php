<?php 
	// error_reporting(E_ALL | E_STRICT);
	// ini_set ('display_errors', true);

	// require_once 'sqli.php'; 
	// require_once 'thread.php'; 
	// require_once 'post.php'; 
	// $sqli = new SQLI('127.0.0.1', 'root', '' , 'sqli_demo'); // You'll want to use a config file so you don't need reinstantiate every single time. This is for demo. 
	// throw new Exception("Uncaught exception wowowwowowo"); 
	// $asdf = new World()
	
	var_dump( error_reporting() );

	die('asdfWorld3');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forum Index</title>

	<link rel="stylesheet" type="text/css" href="bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="site.css">
  </head>
  <body>

    <div class="navbar-wrapper">
        <nav class="navbar navbar-inverse navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              </button>
              <a class="navbar-brand" href="#">SQLI</a>
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
	  						<h4><a href='view.php?id=<?=$thread->id?>'><?=$thread->title?></a></h4>
	  						<span class='subtext'>Started by <?=$thread->creator_name?> , <?=date("F d Y h:i a" , strtotime($thread->created_date));?></span>
	  					</td>
	  					<td>
	  						<!-- Post Count -->
	  						<?= $postCounts[$key]['total']; ?>
	  					</td>
	  					<td>
	  						<!-- Last Poster -->
	  						<?= $posts[$key]->creator_name; ?>
	  					</td>
	  				</tr>
		  		<?php endforeach; ?>
	  			</table>
		  	</div>
		  	<div class='contentwrap'>
				<div class='row outform'>
					<!-- Create Thread Form Goes Here. -->
					<form class='form-group' action='createController.php?create=thread' method='post'>
						<label>Your name:</label><input class='form-control' placeholder='Adam Smith' name='creator_name' />
						<label>Thread Title</label><input class='form-control' placeholder='Thread Title' name='title'  />
						<button class='form-control btn btn-success'>Submit</button>
					</form>
				</div>				
			</div> 
    	</div>
    </div>
  </body>
</html>

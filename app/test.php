<?php
	
	print_r( $sqli->exec( 'select name from thread where id = ?' , [1] , MYSQLI_ASSOC ) ); 
	// var_dump($thread); 
?>
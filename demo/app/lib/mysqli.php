<?php

	class My_SQLI extends SQLI{
		public function searchForum($query, $limit = 999, $offset = 0){
			$threadQuery = $this->getClassMakeParams('Thread');
			$mysqli = $this->mysqli; 
		}
	}

?>
<?php

	class My_OSQLI extends O_SQLI{

		/*
			This class is to demonstrate how to add additional functions to O_SQLI. 
		*/


		public function searchThreads($query, $limit = 999, $offset = 0){
			return $this->where('Thread', 'where title like ?' ,  [ "%$query%"] ); 
		}

		public function getThreadReplies($threadIds){
			if( count($threadIds) === 0 ) return array();
			$threadKey = $this->getClassPrimaryKey('Thread'); 
			$postKey = $this->getClassPrimaryKey('Post'); 
			$threadTable = $this->getClassTable('Thread'); 
			$postTable = $this->getClassTable('Post'); 

			$idString = ""; 
			$arrayKeys = array_keys($threadIds); $endKey =  count($arrayKeys) - 1; 
			foreach($threadIds as $key => $threadId){ $idString .= "?"; if( $arrayKeys[$key] !== $arrayKeys[$endKey] ){ $idString .= " , "; } }
			$query = "select t.`$threadKey` , count(p.`$postKey`) total from `$postTable` p right join `$threadTable` t on p.`tid` = t.`$threadKey` where t.`$threadKey` in ( $idString ) group by t.`$threadKey` "; 

			$ids = array_values($threadIds);

			return $this->exec( $query , $ids , MYSQL_ASSOC );
		}

		public function getLastThreadPost($threadIds){
			if( count($threadIds) === 0 ) return array();

			$threadKey = $this->getClassPrimaryKey('Thread'); 
			$postKey = $this->getClassPrimaryKey('Post'); 
			$threadTable = $this->getClassTable('Thread'); 
			$postTable = $this->getClassTable('Post'); 

			$idString = ""; 
			$arrayKeys = array_keys($threadIds); $endKey =  count($arrayKeys) - 1; 
			foreach($threadIds as $key => $threadId){ $idString .= "?"; if( $arrayKeys[$key] !== $arrayKeys[$endKey] ){ $idString .= " , "; } }
			
			$ids = array_values($threadIds);

			$posts = $this->where('Post', " inner join $threadTable t on `$postTable`.`tid` = t.`$threadKey` where t.`$threadKey` in ( $idString ) group by t.`$threadKey` " , $ids );

			return $posts; 
		}

	}

?>
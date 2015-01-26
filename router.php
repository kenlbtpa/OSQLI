<?php
	class Router{
		private $_urls; 

		public $_notfoundDirectory = '/SQLI/404';
		public $_indexDirectory = '/'; 

		public function __constructor(){ $this->_urls = array(); }

		public function add($url , $dest){
			$this->_urls[] = [ 'src' => $url , 'dest' => $dest ]; 
		}

		public function route(){
			$url = !isset( $_REQUEST['uri'] ) ? $this->_indexDirectory : $_REQUEST['uri'];

			foreach($this->_urls as $key => $val){
				// echo $val['src']; echo '<br/>'; 
				if( preg_match( $val['src'] , $url ) === 1 ){ 
					if( is_callable($val['dest']) )
						$dest = $val['dest']($url); 
					else
						$dest = $val['dest']; 

					if( file_exists($dest) ){
						require_once 'init.php'; 
						require_once $dest; 
						return;
					}
				}
			}

			/*Not Found*/
			header("Location: " . $this->_notfoundDirectory ); 
		}
	}; 

	function router_prepareUrl($url){
		return strtolower( trim($url , '/') ); 
	}

	function router_appendPHPExt($url){
		return $url . '.php'; 
	}


?>
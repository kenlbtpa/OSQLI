<?php

class Post{

	/*SQLI_PARAMETERS*/
	public static $TABLE_NAME = "post"; 
	public static $TABLE_FIELDS = 
	[
		'id' => [ 'field_name' => 'id', 'primary_key' => true , 'auto_increment' =>true , "type" =>'int'] , 
		'tid' =>['field_name' => 'thread_id' , 'not_null' => true , 'not_empty' => true , 'verify' => true ] , 
		'name' =>['field_name' => 'creator_name' , 'not_null' => true , 'not_empty' => true , 'verify' => true ] , 
		'content' =>['field_name' => 'content' , 'not_null' => true , 'not_empty' => true , 'verify' => true ] , 
		'created_date' =>['field_name' => 'created_date' , 'not_null' => true , 'not_empty' => true , 'verifyFrom' => true ] , 
	]; 		

	public $id; 
	public $thread_id; 
	public $creator_name; 
	public $content; 
	public $created_date; 

	public function __construct($id = null , $thread_id = null , $creator_name = null , $content = null , $created_date = null ){
		$this->id = $id; 
		$this->thread_id = $thread_id; 		
		$this->creator_name = $creator_name; 		
		$this->content = $content; 		
		$this->created_date = $created_date; 		
	}

	public static function OSQLI_Factory( $id, $thread_id , $creator_name , $content , $created_date = null ){
		
		$created_name = htmlentities($creator_name); 
		$content = htmlentities($content); 
		$created_date = $created_date === null ? null : htmlentities($created_date); 
		$post = new Post( $id , $thread_id , $creator_name , $content , $created_date ); 
		return $post;
	}


}; 

?>
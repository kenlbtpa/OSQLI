<?php

class Thread{

	/*
	MySQL Table Parameters
	create table thread( id int primary key auto_increment , name varchar(2555) not null , title varchar(2555) not null , created_date datetime not null default current_timestamp ); 
	*/


	/*SQLI_PARAMETERS*/
	public static $TABLE_NAME = "thread"; 
	public static $TABLE_FIELDS = 
	[
		'id' => [ 'field_name' => 'id', 'primary_key' => true , 'auto_increment' =>true , "type" =>'int'] , 
		'name' =>['field_name' => 'creator_name' , 'not_null' => true , 'not_empty' => true , 'verify' => true ], 
		'title' =>['field_name' => 'title' , 'not_null' => true , 'not_empty' => true , 'verify' => true ], 
		'created_date' =>['field_name' => 'created_date' , 'not_null' => true , 'not_empty' => true , 'verifyFrom' => true ] , 
	]; 	


	public $id; 
	public $creator_name; 
	public $title; 
	public $created_date; 

	public function __construct($id = null, $creator_name = null , $title = null , $created_date = null )
	{
		$this->id = $id; 
		$this->creator_name = $creator_name; 
		$this->title = $title; 
		$this->created_date = $created_date; 
	}

	public static function OSQLI_Factory( $id , $creator_name , $title , $created_date = null ){
		
		$created_name = htmlentities($creator_name); 
		$title = htmlentities($title); 
		$created_date = $created_date === null ? null : htmlentities($created_date); 

		return new Thread( $id , $creator_name , $title , $created_date ); 
	}
}; 


?>
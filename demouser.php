<?php

	class User
	{
		public static $TABLE_NAME = "User"; 
		/*dbname=>[...]*/
		public static $TABLE_FIELDS = 
		[
			'id' => [ 'field_name' => 'id', 'primary_key' => true , 'auto_increment' =>true , "type" =>'int'] , 
			'email' => [ 'verify' => true , 'not_null' => true , 'unique' => true , 'not_empty' => true , 'type' => 'string'] , 
			'password' => [ 'verify' => true , 'not_null' => true , 'not_empty' => true , 'type' => 'string'] , 
			'firstname' => [ 'verify' => true, 'not_null' => true , 'not_empty' => true , 'type' => 'string' ] , 
			'lastname' => [ 'verify' => true,  'not_null' => true , 'not_empty' => true , 'type' => 'string' ] , 
			'lastActive' => [ 'not_null' => true , 'type' => 'datetime' ] , 
			'lastModified' => [ 'not_null' => true , 'type' => 'datetime' ] , 
			'deleted' => [ 'field_name' => 'removed' , 'not_null' => true , 'default' => 0 , 'type' => 'bool' ] , 
		]; 

		public static $PRIMARY_KEY = 'id'; // not necessary to define, if already defined in table_fields. 

		public $id; 
		public $email; 
		public $password;
		public $firstname;
		public $lastname;
		public $lastActive;
		public $lastModified;
		public $removed;

		public function __construct($id = null, $email = null, $firstname = null, $lastname = null, $lastActive = null, $lastModified = null, $removed = null)
		{
			$this->id = $id; 
			$this->email = $email; 
			$this->firstname = $firstname; 
			$this->lastname = $lastname; 
			$this->lastModified = $lastModified; 
			$this->removed = $removed; 
		}

	};

?>
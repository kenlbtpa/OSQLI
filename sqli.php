<?php
	
	class SQLI
	{
		public $mysqli; 
		public $models; 

		public function __construct()
		{
			/*There are no use for all your gimmicks.*/
			$mysqli = new Mysqli( SQL_HOST , SQL_USER , SQL_PASS ); 

			if ($mysqli->connect_errno){
				throw new Excpetion("Could not initiate connection to mysql server.".$mysqli->connection_errno);
			}

			if( !$mysqli->select_db(SQL_DB) ){
				if( !$mysqli->query( 'CREATE DATABASE '.SQL_DB ) )
				{
					throw new Exception("Could not create database(".$mysqli->error.")"); 
				}
				$mysqli->select_db(SQL_DB); 
			}

			$this->mysqli = $mysqli; 
			$this->models = []; 
		}

		public function prepareModel($class)
		{
			if( isset($this->models[$class]) ){return;}
			$this->models[$class] = new SQLI_Model($class); 
		}

		public function getModel($class)
		{
			return $this->models[$class]; 
		}

		/*
		* Makes an the specified class object using an associative array.
		*/
		public function make($class, $assoc)
		{
			$this->prepareModel($class); 
			$model = $this->getModel($class);
			return $model->buildFromArray($assoc); 						
		}

		/*
		* Tests if the table has an object with the primary key.
		*/
		public function exists($object)
		{
			$this->prepareModel(get_class($object)); 
			$model = $this->getModel(get_class($object));

			$mysqli = $this->mysqli; 
			$class = get_class($object); 
			$tableName = $model->tableName; 
			$primaryField = $model->primaryField; 
			$primaryKey = $primaryField->dbName; 
			$opv = $primaryField->fieldName; 
			$primaryValue = $object->$opv; 
			$query = "SELECT * from `$tableName` where `$primaryKey` = ?;";
			$stmt = $mysqli->prepare($query); 
			if( !$stmt ){ throw new Exception("Could not prepare ".__FUNCTION__."(".$mysqli->error.")"); }
			$stmt->bind_param('s', $primaryValue); 
			if( !$stmt->execute() ){ throw new Exception("Could not execute ".__FUNCTION__."(".$stmt->error.")"); }
			$stmt->store_result(); 
			return ( $stmt->num_rows > 0 ); 
		}

		/*
		* Attempts to Save an Object. If does not exist create, else update. 
		*/
		public function save($object)
		{
			$this->prepareModel(get_class($object)); 
			$model = $this->getModel(get_class($object));

			$mysqli = $this->mysqli; 

			$class = get_class($object); 
			$tableName = $model->tableName; 
			$pField = $model->primaryField;
			$pKey = $pField->dbName; 
			$opv = $pField->fieldName; 
			$pValue = $object->$opv; 

			$fields = $model->modelFields; 

			$objectProps = get_object_vars($object); 
			$c = 0; 
			$paramString = ''; 
			$valueString = ''; 
			$setString = '';

			$validationResults = []; 
			if(!$model->validateToDatabaseTransaction($object, $validationResults)){
				throw new Exception(json_encode($validationResults));
			}

			$dbVals = $model->prepareGetTransaction($object); 

			foreach($dbVals as $dbKey => $dbVal)
			{
				$paramString .= "`$dbKey`";
				$val = $mysqli->real_escape_string($dbVal);  
				$valueString .= "'$val'";
				$setString .= " `$dbKey` = '$val' ";
				if( $c++ !== count($dbVals) - 1 ){ $paramString .= " , "; $valueString .= " , "; $setString .= " , "; }				
			}

			$query = 
			"INSERT INTO `$tableName` ( $paramString ) VALUES( $valueString ) ON DUPLICATE KEY UPDATE    
			$setString"; 


			if( $mysqli->query($query) === false )
			{
				throw new Exception("Could not query ".__FUNCTION__."(".$mysqli->error.")"); 
			}

			return $mysqli->insert_id; 
		}

		/*
		* Returns the objects matching the primary key and the prefix. 
		*/
		public function get($class , $pk , $suffix = null)
		{
			$this->prepareModel($class); 
			$model = $this->getModel($class);

			$mysqli = $this->mysqli; 
			$tableName = $model->tableName; 
			$GETParams = $model->getParams(); 
			$primaryKey = $model->primaryField->dbName; 
			$query = "SELECT $GETParams from `$tableName` where `$primaryKey` = ? $suffix ; ";
			$stmt = $mysqli->prepare($query); 
			if( !$stmt ){ throw new Exception("Could not prepare ".__FUNCTION__."(".$mysqli->error.")"); }
			
			$stmt->bind_param( 's' , $pk ); 

			$bindResults = [];
			foreach($model->modelFields as $key => $field ) { $name = $field->fieldName; $bindResults[] = &$$name; }
			call_user_func_array( array($stmt, "bind_result") , $bindResults); 

			if( !$stmt->execute() ){ throw new Exception("Could not execute ".__FUNCTION__."(".$stmt->error.")"); }

			if($stmt->fetch()) {
				$object = $model->buildFromBindResults($bindResults); 
				$error =[]; 
				if( !$model->validateFromDatabaseTransaction($object, $error) ){ $error['object']=$object; throw new Exception(json_encode($error)); }
				return $object; 
			}
			return false;
		}

		/*
		* Returns the first object matching 
		*/
		public function find($class , $suffix = null)
		{
			$this->prepareModel($class); 
			$model = $this->getModel($class);

			$mysqli = $this->mysqli; 
			$tableName = $model->tableName; 
			$GETParams = $model->getParams(); 
			$primaryKey = $model->primaryField->dbName; 
			if(is_array($suffix)){
				$sufwhere = isset($suffix['where']) ? $suffix['where'] : $suffix[0]; 
				$query = "SELECT $GETParams from `$tableName` where $sufwhere ; ";
			}
			else
			{
				$query = "SELECT $GETParams from `$tableName` $suffix ; ";
			}
			
			$stmt = $mysqli->prepare($query); 
			if( !$stmt ){ throw new Exception("Could not prepare ".__FUNCTION__."(".$mysqli->error.")"); }
			
			$bindParams = []; 
			if( is_array($suffix) )
			{
				$sufwhere = isset($suffix['where']) ? $suffix['where'] : $suffix[0]; 
				$sufparams = isset($suffix['params']) ? $suffix['params'] : $suffix[1];
				if( false !== stripos( $sufwhere , '?' ) ){
					$binds = substr_count( $sufwhere , '?' ); 
					$bindString = str_repeat('s', $binds); 
					$bindVars = []; foreach($sufparams as $key => $sp){ $bindVars[] = &$sufparams[$key]; }
					$bindParams = $bindVars; 
					array_unshift($bindParams , $bindString); 
					call_user_func_array( array($stmt, "bind_param") , $bindParams); 								
				}
			}

			$bindResults = [];
			foreach($model->modelFields as $key => $field ) { $name = $field->fieldName; $bindResults[] = &$$name; }
			call_user_func_array( array($stmt, "bind_result") , $bindResults); 

			if( !$stmt->execute() ){ throw new Exception("Could not execute ".__FUNCTION__."(".$stmt->error.")"); }

			if($stmt->fetch()) {
				$object = $model->buildFromBindResults($bindResults); 
				$error =[]; 
				if( !$model->validateFromDatabaseTransaction($object, $error) ){ $error['object']=$object; throw new Exception(json_encode($error)); }
				return $object; 
			}
			return false;
		}

		/*
		* Returns an array of objects matching the suffix. If none is provided, returns all. 
		*/
		public function where($class , $suffix = null)
		{
			$this->prepareModel($class); 
			$model = $this->getModel($class);

			$mysqli = $this->mysqli; 
			$tableName = $model->tableName; 
			$GETParams = $model->getParams(); 
			$query = "SELECT $GETParams from `$tableName` $suffix ; ";
			$stmt = $mysqli->prepare($query); 
			if( !$stmt ){ throw new Exception("Could not prepare ".__FUNCTION__."(".$mysqli->error.")"); }

			$bindResults = [];
			foreach($model->modelFields as $key => $field ) { $name = $field->fieldName; $bindResults[] = &$$name; }
			call_user_func_array( array($stmt, "bind_result") , $bindResults); 

			if( !$stmt->execute() ){ throw new Exception("Could not execute ".__FUNCTION__."(".$stmt->error.")"); }

			while($stmt->fetch()) {
				$object = $model->buildFromBindResults($bindResults); 
				$error =[]; 
				if( !$model->validateFromDatabaseTransaction($object, $error) ){ $error['object']=$object; throw new Exception(json_encode($error)); }
				$list[] = $object; 
			}
			return $list;
		}
	}

	/*
	*
	*/
	function sqli_makeClassGETParams($class)
	{
		$GETParams = "";
		foreach($class::$TABLE_FIELDS as $key => $field)
		{
			$fieldName = $field[0]; 
			$GETParams .= "`$fieldName`"; 
			if( $key !== count( $class::$TABLE_FIELDS ) - 1 ){ $GETParams .= ' , '; }
		}
		return $GETParams; 
	}


	class SQLI_ModelField
	{
		public $fieldName; // the name of the param in the object. 

		/* Link back to SQLI_Model */
		public $sqliModel; 

		public $dbName; // the name of the param in the db. 
		public $isPrimary;
		public $isAutoIncrement; 
		public $fieldType;
		public $isUnique; 
		public $isNotNull; 
		public $isNotEmpty; 
		public $defaultValue; // mixed type. 

		public $verifyTo; // if true, when updating or inserting, the object will be verified. 
		public $verifyFrom; // if true, when retrieveing the object will be verified.  

		private function __construct( $dbName , $isPrimary, $isAutoIncrement , $fieldType , $isUnique , $isNotNull , $isNotEmpty ,
			$defaultValue , $verifyTo , $verifyFrom,  $fieldName , SQLI_Model &$sqliModel)
		{
			$this->dbName = $dbName;
			$this->isPrimary = $isPrimary; 
			$this->isAutoIncrement = $isAutoIncrement; 
			$this->fieldType = $fieldType; 
			$this->isUnique = $isUnique; 
			$this->isNotNull = $isNotNull; 
			$this->isNotEmpty = $isNotEmpty; 
			$this->defaultValue = $defaultValue; 

			$this->verifyTo = $verifyTo; 
			$this->verifyFrom = $verifyFrom; 

			$this->fieldName = $fieldName; 
			$this->sqliModel = $sqliModel; 
		}

		/*
		* Factory from array.
		*/
		public static function MakeFromArray( $array , $dbName, SQLI_Model &$model )
		{
			$array = array_change_key_case($array); 
			$fieldName = isset( $array["field_name"] ) ? $array["field_name"] : $dbName; 
			$isPrim = isset( $array["primary_key"] ) ? $array["primary_key"] : false; 
			$isAuto = isset( $array["auto_increment"] ) ? $array["auto_increment"] : false; 
			$fieldType = isset( $array["type"] ) ? $array["type"] : 'string'; 
			$isUnique = isset( $array["unique_key"] ) ? $array["unique_key"] : false; 
			$isNotNull = isset( $array["not_null"] ) ? $array["not_null"] : false; 
			$isNotEmpty = isset( $array["not_empty"] ) ? $array["not_empty"] : false; 
			$defaultValue = isset( $array["default"] ) ? $array["default"] : null; 
			
			$verify = isset( $array["verify"] ) ? $array["verify"] : false; 
			$verifyTo = isset( $array["verifyTo"] ) ? $array["verifyTo"] : false; 
			$verifyFrom = isset( $array["verifyFrom"] ) ? $array["verifyFrom"] : false; 

			$verifyTo = ($verifyTo || $verify);
			$verifyFrom = ($verifyFrom || $verify);

			return new SQLI_ModelField( $dbName , $isPrim , $isAuto , $fieldType , $isUnique , $isNotNull , $isNotEmpty , $defaultValue , $verifyTo , $verifyFrom , $fieldName , $model ); 
		}
	}

	class SQLI_Model
	{
		public $tableName; // the name of the table in the database. 
		public $classModel; // a link to the class. 
		public $primaryField; // a link to the modelfield that is the primary key.
		public $modelFields; 

		public function __construct($class)
		{
			$this->tableName = property_exists($class, 'TABLE_NAME') ? $class::$TABLE_NAME : $class; 
			$this->classModel = $class;
			$tblFields = $class::$TABLE_FIELDS; 
			$this->modelFields = []; 
			foreach($tblFields as $key => $field)
			{
				$dbName = $key; 
				$modelField = SQLI_ModelField::MakeFromArray( $field , $dbName , $this ); 
				$this->modelFields[$key] = $modelField; 
				if( $modelField->isPrimary ) { 
					if($this->primaryField != null){ throw new Exception("Duplicate Primary Key Found.");  } 
					referenceBind($this->primaryField, $modelField); 
				}
			}
			$this->primaryField = self::getField($class::$PRIMARY_KEY); 

			$this->primaryField->isUnique=true;
			$this->primaryField->isNotNull=true;
		}

		public function getField($key){ return $this->modelFields[$key]; }
		public function getPrimaryField($key){ return $this->primaryField; }
		public function getPrimaryKey(){ return $this->primaryField->dbName; }

		public function getVerifyToFields(){ 
			$fields = []; 
			foreach($this->modelFields as $key => $field){
				if($field->verifyTo == true){ $fields[] = $field; }
			}
			return $fields; 
		}

		public function getVerifyFromFields(){ 
			$fields = []; 
			foreach($this->modelFields as $key => $field){
				if($field->verifyFrom == true){ $fields[] = $field; }
			}
			return $fields; 
		}

		/*
		* Used for Validation of object.
		* Private, called by validatetoDatabaseTransaction & validateFromDatabaseTransaction
		*/
		private function validateDatabaseTransaction($object, $validateFields , &$errors = array())
		{
			$errors = []; 
			foreach($validateFields as $key => $field)
			{
				$fieldName = $field->fieldName; 
				$val = $object->$fieldName; 
				if( ($field->isNotNull && $val === null) )
				{ 
					if($field->defaultValue === null)
					{
						$error = [ "message" => sprintf("The field %s must not be null" , $fieldName) ,  "fieldname" => $fieldName , "constraint" => "not_null" ]; 
						$errors[] = $error; 						
					}
					else
					{
						$val = $field->defaultValue; 
						$object->$fieldName = $val; 
					}
				}
				if( trim($val) === '' && $field->isNotEmpty )
				{
					if($field->defaultValue === null)
					{
						$error = [ "message" => sprintf("The field %s must not be empty" , $fieldName) ,  "fieldname" => $fieldName , "constraint" => "not_empty" ]; 
						$errors[] = $error; 						
					}
					else
					{
						$val = $field->defaultValue; 
						$object->$fieldName = $val; 
					}					
				}
			}
			return empty($errors); 			
		}

		/*
		* Used for Validation to Database. 
		* Checks if the object is valid according to the model. The restraints are checked, only if valid constraint for the field is set true. 
		* Returns False if there is an error. True if the object is safe. 
		*/
		public function validateToDatabaseTransaction($object, &$errors = array())
		{
			$validateFields = $this->getVerifyToFields();
			return $this->validateDatabaseTransaction($object, $validateFields, $errors); 
		}

		/*
		* Used for Validation From Database. 
		* Checks if the object is valid according to the model. The restraints are checked, only if valid constraint for the field is set true. 
		* Returns False if there is an error. True if the object is safe. 
		*/
		public function validateFromDatabaseTransaction($object, &$errors = array()) // validation occurs after fetching from database. 
		{
			$validateFields = $this->getVerifyFromFields();
			return $this->validateDatabaseTransaction($object, $validateFields, $errors); 
		}

		/*
		* Takes the object of the class and returns an array of fields relevant to the transaction.  
		*/
		public function prepareGetTransaction($obj)
		{
			$obv = []; 
			foreach($this->modelFields as $key => $field)
			{
				$fieldName = $field->fieldName;
				if( !property_exists($obj, $fieldName) ){ continue; }
				$val = $obj->$fieldName; 
				if($val === null){ continue; }
				if( is_bool($val) ){ $val = ($val) ? 'true' : 'false'; }
				else if( is_array($val) ){ $val = stripcslashes(serialize($val)); }

				$obv[$field->dbName] = $val;
			}
			return $obv; 
		}

		/*
		* Builds the Object from AssociativeArray
		*/
		public function buildFromArray($assoc)
		{
			$obj = new $this->classModel;
			foreach($this->modelFields as $key => $field)
			{
				$fieldName = $field->fieldName; 
				if(!isset($assoc[$fieldName])){continue;}
				$value = $assoc[$fieldName];
				$obj->$fieldName = $value; 
			}
			return $obj;
		}


		/*
		* Builds the Object from BindResults
		*/
		public function buildFromBindResults($bindResults)
		{
			$obj = new $this->classModel;
			$c = 0; 
			foreach($this->modelFields as $key => $field)
			{
				$result = $bindResults[$c++];
				$fieldName = $field->fieldName; 
				$obj->$fieldName = $result; 
			}
			return $obj;
		}

		/*
		* Builds The Get Command. 
		*/
		public function getParams()
		{
			$getQuery = ""; $c = 0; 
			foreach($this->modelFields as $key => $field)
			{
				$getQuery .= "`".stripcslashes($field->dbName)."`"; 
				if( $c++ !== count($this->modelFields) - 1 ){ $getQuery .= " , "; }				
			}
			return $getQuery;
		}
	}

	function referenceBind(&$dest, &$source)
	{
		$dest = $source;
	}
?>
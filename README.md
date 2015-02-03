OSQLI
===================

O_SQLI Purpose
--------------------
 A MySQL Extension that enables basic interaction between MySQL and PHP Objects. 

O_SQLI Requirements
--------------------
PHP 5.4

O_SQLI Sample Usage
--------------------
```PHP
$sqli = new O_SQLI( /*...*/ ); // fill in your mysql connection information. 
$threadBuild = $_POST;
$thread = new Thread( null , "Sample Creator" , "Sample Title" ); // Creates a Thread Object with no id. 
$res = $sqli->save($thread); // O_SQLI will proceed to insert the thread object into the database. 
```

O_SQLI Usage
--------------------

### Instantiating O_SQLI Object. 
 O_SQLI is an extension of MySQLI. Instantiating is the same. 
```PHP
$sqli = new O_SQLI(SQL_HOST, SQL_USER , SQL_PASS , SQL_DB); 
```


### Setting up an object for O_SQLI
 O_SQLI requires several parameteres to be setupped.

 Table Fields should be specified as a static variable called $TABLE_FIELDS that is an associative array of associative arrays. 
 The key of the associative array will be the field name on the db. By default it is assumed that the db field matches the field name of the object. 
 Further information about the db field could be specified as an associative array as seen below. 

```PHP
public static $TABLE_FIELDS = 
[
  'id' => [ 'field_name' => 'id', 'primary_key' => true , 'auto_increment' =>true , "type" =>'int'] , 
  'name' =>['field_name' => 'creator_name' , 'not_null' => true , 'not_empty' => true , 'verify' => true ], 
  'title' =>['field_name' => 'title' , 'not_null' => true , 'not_empty' => true , 'verify' => true ], 
  'created_date' =>['field_name' => 'created_date' , 'not_null' => true , 'not_empty' => true , 'verifyFrom' => true ] , 
];    
```

##### field_name
Specifies the name of the field in the PHP object. 

  ##### primary_key
  Specifies if the field is a primary key. 

  ##### not_null
  Specifies if the field is not null. This is a type of constraint.

  ##### not_empty
  Specifies if the field is not empty. This is a type of constraint. 

  ##### verify_to
  Checks if the constraints apply to the object. If not, an error will be returned. This only applies when an object is being passed to the database. 

  ##### verify_from
  Checks if the constraints apply to the object. If not, an error will be returned. This only applies when an fields is fetched from the database, successfully built into an object. Then the verification occurs. 

  ##### verify
  Checks if the constraints apply to the object. If not, an error will be returned. This function is equivalent to verify_to and verify_from


  You will also need a Factory Class. This function specifies O_SQLI how to build the objects after retrieving from database. When sending an object to the database from O_SQLI, you may use the default constructor but when an object is being built from the fields retrieved from the database the factory function is used. 

```PHP
public static function OSQLI_Factory( $id , $creator_name , $title , $created_date = null ){
  
  $created_name = htmlentities($creator_name); 
  $title = htmlentities($title); 
  $created_date = $created_date === null ? null : htmlentities($created_date); 

  return new Thread( $id , $creator_name , $title , $created_date ); 
}
```

DEMO
--------------------
Use the demo provided. 
  

O_SQLI Functions
--------------------
### o_sqli.exists($object)
 Takes in an object and checks if the primary key exists in the database.   
  ### Sample Code
```PHP
$thread = new Thread(1 , "Guess Poster" , "Hobbies" , null );
var_dump( $osqli->exists($thread) ); 
```
  ### Output
```PHP
bool(false) // does not exist yet. 
```

### o_sqli.save($object)
 If the object does not exist in the database, insert into database. If the object does exist in the database, update the record.  
        ###Thread with id=1 does not exist in table `Thread`. 
```PHP
$thread = new Thread(1 , "Guess Poster" , "Hello World!" , null );
$osqli->save($thread); //Thread is created and inserted into the database.
```
        ###Using the previous $thread, we'll change the title and update it on the database.
```PHP
$thread->title = "First Thread!";
$osqli->save($thread);
```

### o_sqli.get($className, $primaryKey , $suffix = null)
 Attempts to look for a object in the database that matches the primary key. Suffix can be used to provide additional search constraints on the transaction.   
  ### Sample Code
```PHP
var_dump( $osqli->get('Thread', 1 ) ); 
```
  ### Output
```
object(Thread)[12]
    public 'id' => int 1
    public 'creator_name' => string 'Guess Poster' (length=12)
    public 'title' => string 'First Thread!' (length=12)
    public 'created_date' => string '2015-01-20 02:19:31' (length=19)
```
### o_sqli.where($className, $suffix = null , $params = null): 
 Returns a list of objects that match that match the suffix. If the suffix uses mysqli_prepare marks "?" , then use supply its respective data via params. 
  ### Sample Code
```PHP  
var_dump( $osqli->where('Thread', 'where id in ( ? , ? ) group by id' , [1,2] ) ); 
```
  ### Output
```
array (size=2)
  0 => 
    object(Thread)[12]
      public 'id' => int 1
      public 'creator_name' => string 'General User' (length=12)
      public 'title' => string 'First Thread' (length=12)
      public 'created_date' => string '2015-01-20 02:19:31' (length=19)
  1 => 
    object(Thread)[13]
      public 'id' => int 2
      public 'creator_name' => string 'TEST' (length=4)
      public 'title' => string 'TEST' (length=4)
      public 'created_date' => string '2015-01-25 22:11:52' (length=19)
```

### o_sqli.find($className, $suffix = null , $params = null): 
 Returns the first result of where. If no result is found then null is returned. 
  ### Sample Code
```PHP
var_dump( $osqli->find('Thread', 'where id = ? ' , [1] ) ); 
```
  ### Output
```
object(Thread)[12]
    public 'id' => int 1
    public 'creator_name' => string 'Guess Poster' (length=12)
    public 'title' => string 'First Thread!' (length=12)
    public 'created_date' => string '2015-01-20 02:19:31' (length=19)
```
### o_sqli.count($className, $suffix = null , $params = null): 
 Counts the number of objects that matches the parameters. 
  ### Sample Code
```PHP  
var_dump( $osqli->count('Thread' ) ); 
```
  ### Output
```
int(1)
```

### o_sqli.exec($query, $params = null , $MYSQLI_TYPE = MYSQLI_NUM): 
 Executes the query. You may use params to supply data to parameters if you're using mysqli_prepare syntax. 
 $MYSQLI_TYPE determines the type of array that will be returned. 
  ### Sample Code
```PHP
var_dump( $osqli->exec( 'select name from Thread where id = ?' , [1] , MYSQLI_ASSOC ) ); 
```  
  ### Output
```PHP
Array ( [0] => Array ( [name] => Guess Poster ) )
```

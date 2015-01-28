OSQLI
===================

O_SQLI Functions
--------------------
* ## o_sqli.exists($object)
 Takes in an object and checks if the primary key exists in the database.   
  ### Sample Code
        $thread = new Thread(1 , "Guess Poster" , "Hobbies" , null );
        var_dump( $osqli->exists($thread) ); 
  ### Output
        bool(false) // does not exist yet. 

* ## o_sqli.save($object)
 If the object does not exist in the database, insert into database. If the object does exist in the database, update the record.  
        ###Thread with id=1 does not exist in table `Thread`. 
        $thread = new Thread(1 , "Guess Poster" , "Hello World!" , null );
        $osqli->save($thread); //Thread is created and inserted into the database.

        ###Using the previous $thread, we'll change the title and update it on the database.
        $thread->title = "First Thread!";
        $osqli->save($thread);

* ## o_sqli.get($className, $primaryKey , $suffix = null)
 Attempts to look for a object in the database that matches the primary key. Suffix can be used to provide additional search constraints on the transaction.   
  ### Sample Code
        var_dump( $osqli->get('Thread', 1 ) ); 
  ### Output
        object(Thread)[12]
            public 'id' => int 1
            public 'creator_name' => string 'Guess Poster' (length=12)
            public 'title' => string 'First Thread!' (length=12)
            public 'created_date' => string '2015-01-20 02:19:31' (length=19)

* ## o_sqli.where($className, $suffix = null , $params = null): 
 Returns a list of objects that match that match the suffix. If the suffix uses mysqli_prepare marks "?" , then use supply its respective data via params. 
  ### Sample Code
        var_dump( $osqli->where('Thread', 'where id in ( ? , ? ) group by id' , [1,2] ) ); 
  ### Output
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

* ## o_sqli.find($className, $suffix = null , $params = null): 
 Returns the first result of where. If no result is found then null is returned. 
  ### Sample Code
        var_dump( $osqli->find('Thread', 'where id = ? ' , [1] ) ); 
  ### Output
        object(Thread)[12]
            public 'id' => int 1
            public 'creator_name' => string 'Guess Poster' (length=12)
            public 'title' => string 'First Thread!' (length=12)
            public 'created_date' => string '2015-01-20 02:19:31' (length=19)

* ## o_sqli.count($className, $suffix = null , $params = null): 
 Counts the number of objects that matches the parameters. 
  ### Sample Code
        var_dump( $osqli->count('Thread' ) ); 
  ### Output
        int(1)

* ## o_sqli.exec($query, $params = null , $MYSQLI_TYPE = MYSQLI_NUM): 
 Executes the query. You may use params to supply data to parameters if you're using mysqli_prepare syntax. 
 $MYSQLI_TYPE determines the type of array that will be returned. 
  ### Sample Code
        var_dump( $osqli->exec( 'select name from Thread where id = ?' , [1] , MYSQLI_ASSOC ) ); 
  ### Output
        Array ( [0] => Array ( [name] => Guess Poster ) )


<style>
ul
{
    list-style-type: none;
}
</style>


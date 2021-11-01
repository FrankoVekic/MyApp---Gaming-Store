<?php 

class DB extends PDO
{
    private static $connect = null;

    private function __construct($database)
    {
        $dsn='mysql:host=' . $database['host'] . ';dbname=' . $database['dbName'];

        parent::__construct($dsn,$database['username'],$database['password']);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
    }

    public static function connect()
    {   
        if(self::$connect==null){
            self::$connect = new self(App::config('database'));
        }
        return self::$connect;
    }
}
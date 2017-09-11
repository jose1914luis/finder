<?php
class Database
{
    
    private static $dbName = 'cmqpru' ;
    private static $dbHost = '104.154.186.233' ;
    private static $dbUsername = 'cmqpru';
    private static $dbUserPassword = '2012zygMin';
    private static $port = '5432';
    
     
    private static $cont  = null;
     
    public function __construct() {
        die('Init function is not allowed');
    }
     
    public static function connect()
    {
       // One connection through whole application
       if ( null == self::$cont )
       {     
        try
        {
          
          self::$cont =  new PDO( "pgsql:host=".self::$dbHost.";"."dbname=".self::$dbName.";port=".self::$port.";user=".self::$dbUsername.";password=". self::$dbUserPassword);                         
        }
        catch(PDOException $e)
        {
          die($e->getMessage()); 
        }
       }
       return self::$cont;
    }
      

    public static function disconnect()
    {
        self::$cont = null;
    }
}

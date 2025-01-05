<?php

class Database
{
    private static $dbHost = "localhost";
    private static $dbName = "u171298736_chatapp";
    private static $dbUsername = "u171298736_stcodechatapp";
    private static $dbUserpassword = "15A032stcode@";
    
    private static $connection = null;
    
    public static function connect()
    {
        if(self::$connection == null)
        {
            try
            {
              self::$connection = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName , self::$dbUsername, self::$dbUserpassword);
            }
            catch(PDOException $e)
            {
                die($e->getMessage());
            }
        }
        return self::$connection;
    }
    
    public static function disconnect()
    {
        self::$connection = null;
    }

}
?>
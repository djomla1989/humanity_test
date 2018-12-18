<?php

/**
 * DBConnection class file.
 *
 */

/**
 * Class that is establishing a PDO connection.
 */
class DBConnection 
{
    protected static $defaults = null;
    protected static $dbhs = [];
    
    /**
     * Establishes the database connection. 
     * If connection already exists, we will reuse it.
     * 
     * @param string $dsn
     * @param string $user
     * @param string $pass
     * @param array  $options
     * @param boolean $forceOpenNewConn
     * 
     * @return PDO
     */
    public static function connect($dsn = null, $user = null, $pass = null, $options = null, $forceOpenNewConn = false) 
    {
        $args = array_slice(func_get_args(), 0, 4);
        $fingerprint = md5(serialize($args));
        
        // if we are not forcing new connection and connection with this fingerprint already exists, reuse it
        if (!$forceOpenNewConn && isset(self::$dbhs[$fingerprint])) {
            $conn = self::$dbhs[$fingerprint];
        } else { // create new connection
            self::getDefaults();
            if (!isset($dsn)) $dsn = self::$defaults['dsn'];
            if (!isset($user)) $user = self::$defaults['username'];
            if (!isset($pass)) $pass = self::$defaults['password'];
            if (!isset($options)) $options = self::$defaults['options'];
            
            $conn = new PDO($dsn, $user, $pass, $options);
            // save the fingerprint => connection in dbh signature so we can reuse it if possible
            if (!isset(self::$dbhs[$fingerprint])) self::$dbhs[$fingerprint] = $conn;
        }
        
        return $conn;
    }
    
    /**
     * @return PDO
     */
    public static function getLastConnection() 
    {
        return end(self::$dbhs);
    }
    
    public static function getDefaults() 
    {
        if (!isset(self::$defaults)) {
            self::$defaults = [
                'dsn' => "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET . "",
                'username' => DB_USERNAME,
                'password' => DB_PASSWORD,
                'options' => [
                    PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => false,
                ]
            ];
        }
        return self::$defaults;
    }
}
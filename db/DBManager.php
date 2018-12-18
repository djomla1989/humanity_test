<?php
require('DBConnection.php');

/**
 * DBManager class file.
 *
 */

/**
 * Class that is providing methods for writing and executing database queries.
 * It is using DBConnection for establishing a PDO connection.
 *
 * @requirements: PHP 5.3 or greater
 */
abstract class DBManager
{
    /**
     * Database handle.
     * 
     * @var PDO
     */
    private $dbh;

    /**
     * The sql query.
     * 
     * @var string
     */
    private $query;

    /**
     * Whether or not the user has specified the fetch mode.
     * 
     * @var boolean
     */
    private $fetchMode = false;  

    /**
     * Statement handle
     * 
     * @var object
     */
    protected $sth;

    /**
     * Exception errors.
     * 
     * @var string
     */
    protected $error;

    // connection config
    protected $dbDsn = null;
    protected $dbUser = null;
    protected $dbPass = null;
    protected $dbOptions = null;
    protected $dbForceOpenNewConn = false;
    protected $dbCharset = null;
    protected $dbName = null;
    protected $dbHost = null;

    /**
     * Initializes the PDO database connection
     */
    public function __construct()
    {
        // tries to connect
        try {
            $this->dbh = DBConnection::connect($this->getDbDsn(), $this->dbUser, $this->dbPass, 
                                                $this->dbOptions, $this->dbForceOpenNewConn);
        } catch (PDOException $e) { 
            $this->error = $e->getMessage();      
        }
    }

//-----------------------------------------------------------------------
// Methods for custom query crafting and query execution
//-----------------------------------------------------------------------

    /**
     * Method that is accepting custom crafted sql query.
     * 
     * @param  mixed  $sql Your SQL query. Please use bind() method to bind values!
     * @return object      
     */
    public function query($sql)
    {
        $this->query = $sql;
        $this->sth = $this->dbh->prepare($this->query);      
        return $this;
    }

    /**
     * Public method that will invoke the private one called bindValues().
     * We do not want you to invoke bindValues directly since it is used internally in this class, 
     * and overriding it in wrong way can cause DB class malfunction.
     * If you need to change the way you are doing binding, you can change|override this method.
     * 
     * @param  mixed  $data The array|csv of values to be bound.
     * 
     * @return object
     */
    public function bind($data = [])
    {
        if (!is_array($data)) {
            $data = func_get_args();
        }

        return $this->bindValues($data);
    }

    /**
     * Specifies the fetch mode to be PDO::FETCH_ASSOC
     * 
     * @return $this
     */
    public function asArray()
    {
        $this->fetchMode = $this->sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this;
    }

    /**
     * Executes the select type queries and returns a single record populated with the first row of data. 
     * You need to call this or all() method at the end of queries that are doing database read.
     * 
     * @return One table row.
     */
    public function one()
    {
        if (!$this->fetchMode) {
            $this->sth->setFetchMode(PDO::FETCH_CLASS, ucfirst(get_called_class()));
        } 

        $this->sth->execute();
        return $this->sth->fetch();         
    }

    /**
     * Executes the select type queries and returns all records based on the query results. 
     * You need to call this or one() method at the end of queries that are doing database read.
     * 
     * @return Table rows.
     */
    public function all()
    {
        if (!$this->fetchMode) {
            $this->sth->setFetchMode(PDO::FETCH_CLASS, ucfirst(get_called_class()));
        } 

        $this->sth->execute();
        return $this->sth->fetchAll();         
    }

    /**
     * Method that is executing custom crafted insert/update/delete queries.
     * 
     * @return integer If query is insert, it will return last inserted id, else the number of affected rows.
     */
    public function run()
    {
        $this->sth->execute();

        // if this was an insert, return last inserted id
        if ($this->dbh->lastInsertId()) {
            return $this->dbh->lastInsertId();
        }
       
        // return number of affected rows
        return $this->sth->rowCount();
    }

//-----------------------------------------------------------------------
// Helper methods
//-----------------------------------------------------------------------

    /**
     * Method that is binding values.
     * 
     * @param  array  $data The array with values to be bound.
     * @return object
     */
    private function bindValues($data)
    {
        $i = 1;

        // bind based on value type
        foreach ($data as $key => &$value) {

            // we have the inner array like $data = [[ 0 => 2, 1 => 4], 'not inner']
            if (is_array($value)) {

                foreach ($value as $innerKey => &$innerValue) {

                    if (is_int($innerValue)) {
                        $this->sth->bindParam($i, $innerValue, PDO::PARAM_INT);
                    } elseif (is_bool($innerValue)) {
                        $this->sth->bindParam($i, $innerValue, PDO::PARAM_BOOL);
                    } elseif (is_null($innerValue)) {
                        $this->sth->bindParam($i, $innerValue, PDO::PARAM_NULL);
                    } else {
                        $this->sth->bindParam($i, $innerValue, PDO::PARAM_STR);
                    }
                    $i++;
                }

            } else {

                if (is_int($value)) {
                    $this->sth->bindParam($i, $value, PDO::PARAM_INT);
                } elseif (is_bool($value)) {
                    $this->sth->bindParam($i, $value, PDO::PARAM_BOOL);
                } elseif (is_null($value)) {
                    $this->sth->bindParam($i, $value, PDO::PARAM_NULL);
                } else {
                    $this->sth->bindParam($i, $value, PDO::PARAM_STR);
                }

                $i++;
            }

        } // foreach

        return $this;
    }
    
//-----------------------------------------------------------------------
// Getters and Setters for databse connection configuration
//-----------------------------------------------------------------------

    /**
     *
     * @return string
     */
    protected function getDbDsn() 
    {
        if (!isset($this->dbDsn)) {
            if (isset($this->dbHost) || isset($this->dbName) || isset($this->dbCharset)) {
                $this->dbDsn = "mysql:host=" . $this->getDbHost() . ";" .
                                "dbname=" . $this->getDbName() . ";" . 
                                "charset=" . $this->getDbCharset() . "";
            }
        }
        return $this->dbDsn;
    }
    
    /**
     *
     * @param string $dbDsn
     * @return DB       
     */
    protected function setDbDsn($dbDsn) 
    {
        $this->dbDsn = $dbDsn;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    protected function getDbUser() 
    {
        return $this->dbUser;
    }
    
    /**
     *
     * @param string $dbUser            
     */
    protected function setDbUser($dbUser) 
    {
        $this->dbUser = $dbUser;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    protected function getDbPass() 
    {
        return $this->dbPass;
    }
    
    /**
     *
     * @param string $dbPass            
     */
    protected function setDbPass($dbPass) 
    {
        $this->dbPass = $dbPass;
        return $this;
    }
    
    /**
     *
     * @return array
     */
    protected function getDbOptions() 
    {
        return $this->dbOptions;
    }
    
    /**
     *
     * @param array $dbOptions          
     */
    protected function setDbOptions($dbOptions) 
    {
        $this->dbOptions = $dbOptions;
        return $this;
    }
    
    /**
     *
     * @return bool
     */
    protected function getDbForceOpenNewConn() 
    {
        return $this->dbForceOpenNewConn;
    }
    
    /**
     *
     * @param boolean $dbForceOpenNewConn           
     */
    protected function setDbForceOpenNewConn($dbForceOpenNewConn) 
    {
        $this->dbForceOpenNewConn = $dbForceOpenNewConn;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    protected function getDbCharset() 
    {
        if (!isset($this->dbCharset)) {
            $this->dbCharset = DB_CHARSET;
        }
        return $this->dbCharset;
    }
    
    /**
     *
     * @param string $dbCharset         
     */
    protected function setDbCharset($dbCharset) 
    {
        $this->dbCharset = $dbCharset;
        $this->dbDsn = null;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    protected function getDbName() 
    {
        if (!isset($this->dbName)) {
            $this->dbName = DB_NAME;
        }
        return $this->dbName;
    }
    
    /**
     *
     * @param string $dbName            
     */
    protected function setDbName($dbName) 
    {
        $this->dbName = $dbName;
        $this->dbDsn = null;
        return $this;
    }
    
    /**
     *
     * @return the unknown_type
     */
    protected function getDbHost() 
    {
        if (!isset($this->dbHost)) {
            $this->dbHost = DB_HOST;
        }
        return $this->dbHost;
    }
    
    /**
     *
     * @param unknown_type $dbHost          
     */
    protected function setDbHost($dbHost) 
    {
        $this->dbHost = $dbHost;
        $this->dbDsn = null;
        return $this;
    }
    
}

<?php 

namespace App\Core;

use PDO;
use PDOException;

class Db extends PDO
{
    //only one instance for this class
    private static $instance;

    //connection's informations
    private $host; 
    private $user; 
    private $pwd; 
    private $dbname;

    private function __construct()
    {
        $this->host = $_ENV['MYSQL_HOST'];
        $this->user = $_ENV['MYSQL_USER'];
        $this->pwd = $_ENV['MYSQL_PASSWORD'];
        $this->dbname = $_ENV['MYSQL_DBNAME'];
        // var_dump($_ENV['MYSQL_PASSWORD']);
        // die();
        $_dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname ;
        try{
            // use the constructor of PDO
            parent::__construct($_dsn, $this->user, $this->pwd);

            // setting options
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $exception){
            die($exception->getMessage());
        }
    }

    /**
     * allow to get instance of Db class
     * @return $instance (contains the only Db instance)
     */
    public static function getInstance():self
    {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }


}
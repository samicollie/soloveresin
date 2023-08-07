<?php 

namespace App\Core;
use PDO;
use PDOException;

class Db extends PDO
{
    //only one instance for this class
    private static $instance;

    //connection's informations
    private const HOST = 'localhost';
    private const USER = 'root';
    private const PWD = 'localhost';
    private const DBNAME = 'so-love-resin';

    private function __construct()
    {
        $_dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DBNAME ;
        try{
            // use the constructor of PDO
            parent::__construct($_dsn, self::USER, self::PWD);

            // setting options
            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch(PDOException $exception){
            die($exception->getMessage());
        }
    }

    /**
     * method to allow to get instance of Db class
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
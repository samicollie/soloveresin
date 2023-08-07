<?php

namespace App\Model;

use PDOException;

abstract class Model {
    //database informations
    private $host = "localhost";
    private $db_name ="so-lo-resin";
    private $username ="root";
    private $password = "localhost";

    //property which will contain the instance of the connection
    protected $_connection;

    //properties which allow to custom the requests
    private $table;
    private $id;


    public function getConnection(){
        // delete the previous connection
        $this->_connection = null;

        //new connection 
        try{
            $this->_connection = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name , $this->username, $this->password);
            $this->_connection->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Error of the connection : " . $exception->getMessage();
        }
    }
}
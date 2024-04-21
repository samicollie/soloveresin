<?php

namespace App\Models;

use DateTime;

class Session extends Model
{

    protected $id;
    protected $sessionId;
    protected $userId;
    
    public function __construct()
    {
        $this->table = "Session";
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    

    /**
     * Get the value of sessionId
     */ 
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set the value of sessionId
     *
     * @return  self
     */ 
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get the value of userId
     */ 
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */ 
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * function who return an user session
     *
     * @param string $sessionId
     * @return object
     */
    public function getSession(string $sessionId): object
    {
        $hashSessionId = hash('sha256', $sessionId);
        $sql="SELECT * FROM Session WHERE session_id = ?";
        return $this->request($sql, [$hashSessionId])->fetchObject();
    }

    /**
     * function who add a new user session 
     *
     * @param integer $idUser
     * @return void
     */
    public function addNewSession(string $sessionId, int $idUser=null):void
    {
        $hashSessionId = hash('sha256', $sessionId);
        date_default_timezone_set('Europe/Paris');
        $date = new DateTime();
        $lastAction = $date->format('Y-m-d H:i:s');
        $sql="INSERT INTO Session (session_id, id_user, last_action) VALUES (?, ?, ?)";
        $this->request($sql, [$hashSessionId, $idUser, $lastAction]);
    }

    public function AddIdUserSession(string $sessionId, int $idUser):void
    {
        $hashSessionId = hash('sha256', $sessionId);
        date_default_timezone_set('Europe/Paris');
        $date = new DateTime();
        $lastAction = $date->format('Y-m-d H:i:s');
        $sql="UPDATE Session SET id_user = ?, last_action = ? WHERE session_id = ?";
        $this->request($sql, [$idUser, $lastAction, $hashSessionId]);
    }

    /**
     * update the date of last action of an loggin user
     *
     * @param string $sessionId
     * @return void
     */
    public function updateSession(string $sessionId):void
    {
        $hashSessionId = hash('sha256', $sessionId);
        date_default_timezone_set('Europe/Paris');
        $date = new \DateTime();
        $lastAction = $date->format('Y-m-d H:i:s');
        $sql = "UPDATE Session SET last_action = ? WHERE session_id = ?";
        $this->request($sql, [$lastAction, $hashSessionId]);
    }

    /**
     * function who delete a session
     *
     * @param integer $idUser
     * @return void
     */
    public function deleteSession(int $idUser):void
    {
        $sql="DELETE FROM Session WHERE id_user = ?";
        $this->request($sql, [$idUser]);
    }
}
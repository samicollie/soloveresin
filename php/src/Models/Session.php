<?php

namespace App\Models;

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
     * @return array
     */
    public function getSession(string $sessionId): array
    {
        $sql="SELECT * FROM Session WHERE session_id = ?";
        return $this->request($sql, [$sessionId])->fetchAll();
    }

    /**
     * function who add a new user session 
     *
     * @param integer $idUser
     * @return void
     */
    public function addNewSession(string $sessionId, int $idUser):void
    {
        $sql="INSERT INTO Session (session_id, id_user) VALUES (?, ?)";
        $this->request($sql, [$sessionId, $idUser]);
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
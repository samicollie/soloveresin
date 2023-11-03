<?php 
namespace App\Models;

class Users extends Model
{
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $email;
    protected $password;
    protected $phoneNumber;
    protected $role;
    
    public function __construct()
    {
        $this->table = "Users";
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
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_ARGON2I);

        return $this;
    }

    /**
     * Get the value of phoneNumber
     */ 
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set the value of phoneNumber
     *
     * @return  self
     */ 
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * function who returned if an account exist or not
     *
     * @param string $email
     * @return boolean
     */
    public function verifyUserAccount(string $email): bool
    {
        $sql="SELECT count(id_user) as count_id FROM Users WHERE email = ?";
        $result = $this->request($sql,[$email])->fetch();
        if($result->count_id == 0){
            return false;
        }else{
            return true;
        }
    }

    /**
     * function who add a new user 
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function newUser(string $firstname, string $lastname, string $email, string $password):bool
    {
        $sql = "INSERT INTO Users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)";
        if($this->request($sql, [$firstname, $lastname, $email, password_hash($password, PASSWORD_ARGON2I), '["ROLE_USER"]'])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Function who return an user from email
     *
     * @param string $email
     * @return self
     */
    public function getUser(string $email):array
    {
        $sql="SELECT id_user as id_user, email as email, password as password, role as role  FROM Users WHERE email= ?";
        return $this->request($sql, [$email])->fetchAll();
    }

    /**
     * function who return an user from id_user
     *
     * @param integer $id
     * @return array
     */
    public function getUserById(int $id): array{
        $sql="SELECT * FROM Users WHERE id_user = ?";
        return $this->request($sql, [$id])->fetchAll();
    }

    public function getUserIdBySessionId(string $sessionId): int
    {
        $sql = "SELECT id_user FROM Session WHERE session_id = ?";
        return $this->request($sql, [$sessionId])->fetch()->id_user;
    }

    /**
     * function who update user information and contact
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $phoneNumber
     * @param integer $id
     * @return boolean
     */
    public function updateUser(string $firstname, string $lastname, string $email, string $phoneNumber ,int $id): bool
    {
        $sql ="UPDATE Users SET firstname = ?, lastname = ?, email = ?, phone_number = ? WHERE id_user = ?";
        if($this->request($sql, [$firstname, $lastname, $email, $phoneNumber, $id])){
            return true;
        }else{
            return false;
        }
    }
}
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
    public function isExistingAccount(string $email): bool
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
     * @return object
     */
    public function getUserById(int $id): object
    {
        $sql="SELECT * FROM Users WHERE id_user = ?";
        return $this->request($sql, [$id])->fetchObject();
    }

    

    /**
     * get an user with his reset token
     *
     * @param string $resetToken
     * @return object|null
     */
    public function getUserWithResetToken(string $resetToken): ?object
    {
        $hashToken = hash('sha256', $resetToken);
        $sql="SELECT * FROM Users WHERE reset_token = ?";
        $user = $this->request($sql, [$hashToken])->fetchObject();
        if($user){
            return $user;
        }else{
            return null;
        }
    }

    /**
     * verify if the user is verified
     *
     * @param string $email
     * @return boolean
     */
    public function isVerified(string $email): bool
    {
        $sql="SELECT is_verified FROM Users WHERE email = ?";
        return $this->request($sql, [$email])->fetch()->is_verified;
    }

    /**
     * set account verified
     *
     * @param integer $id
     * @return boolean
     */
    public function makeVerified(int $id): bool
    {
        $sql="UPDATE Users SET is_verified = 1 WHERE id_user = ?";
        if($this->request($sql, [$id])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * get the id_user from his sessionIs (cookie)
     *
     * @param string $sessionId
     * @return integer
     */
    public function getUserIdBySessionId(string $sessionId): int
    {
        $hashSessionId = hash('sha256', $sessionId);
        $sql = "SELECT id_user FROM Session WHERE session_id = ?";
        return $this->request($sql, [$hashSessionId])->fetch()->id_user;
    }

    /**
     * get user with his email
     *
     * @param string $email
     * @return object|null
     */
    public function getUserByEmail(string $email): ?object
    {
        $sql="SELECT * FROM Users WHERE email = ?";
        $user = $this->request($sql, [$email])->fetchObject();
        if($user){
            return $user;
        }else{
            return null;
        }
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
    public function updateUser(string $firstname, string $lastname,string $phoneNumber ,int $id): bool
    {
        $sql ="UPDATE Users SET firstname = ?, lastname = ?, phone_number = ? WHERE id_user = ?";
        if($this->request($sql, [$firstname, $lastname, $phoneNumber, $id])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * update the reset token
     *
     * @param integer $id
     * @param string $resetToken
     * @param string $createdAt
     * @return boolean
     */
    public function updateResetToken(int $id, string $resetToken, string $createdAt): bool
    {
        $hashToken = hash('sha256', $resetToken);
        $sql="UPDATE Users SET reset_token = ?, created_at = ? WHERE id_user = ?";
        if($this->request($sql, [$hashToken, $createdAt, $id])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * update the count link
     *
     * @param integer $id
     * @return boolean
     */
    public function updateCountLink(int $id): bool
    {
        $sql="SELECT count_link FROM Users WHERE id_user = ?";
        $countLink = $this->request($sql, [$id])->fetch()->count_link;
        $sql="UPDATE Users SET count_link = ? WHERE id_user = ?";
        if($this->request($sql, [$countLink, $id])){
            return true;
        }else{
            return false;
        }
    }

    public function updatePassWordWithtoken(string $password, string $resetToken): bool
    {
        $hashPassword = password_hash($password, PASSWORD_ARGON2I);
        $hashToken = hash('sha256', $resetToken);
        $sql = "UPDATE Users SET password = ? WHERE reset_token = ?";
        if($this->request($sql, [$hashPassword, $hashToken])){
            return true;
        }else{
            return false;
        }
    }
}
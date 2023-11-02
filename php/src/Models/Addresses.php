<?php 
namespace App\Models;

class Addresses extends Model
{
    protected $id;
    protected $firstname;
    protected $lastname;
    protected $streetNumber;
    protected $streetName;
    protected $zipcode;
    protected $city;

    public function __construct()
    {
        $this->table = "Adresses";
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
     * Get the value of streetNumber
     */ 
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * Set the value of streetNumber
     *
     * @return  self
     */ 
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get the value of streetName
     */ 
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * Set the value of streetName
     *
     * @return  self
     */ 
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;

        return $this;
    }

    /**
     * Get the value of zipcode
     */ 
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set the value of zipcode
     *
     * @return  self
     */ 
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * get all addresses of an user
     *
     * @param integer $idUser
     * @return array
     */
    public function getAddresses(int $idUser): array
    {
        $sql="SELECT A.id_address, A.firstname, A.lastname, A.street_number, A.street_name, A.zipcode, A.city FROM Addresses A
        JOIN Users_Addresses UA ON UA.id_address = A.id_address
        JOIN Users U ON U.id_user = UA.id_user 
        WHERE U.id_user = ?";
        return $this->request($sql, [$idUser])->fetchAll();
    }

    /**
     * return an address thanks to the id
     *
     * @param integer $id
     * @return array
     */
    public function getAddress(int $id): array
    {
        $sql = "SELECT * FROM Addresses WHERE id_address = ?";
        return $this->request($sql, [$id])->fetchAll();
    }

    /**
     * add an address for an user in the database
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $streetNumber
     * @param string $streetName
     * @param string $zipcode
     * @param string $city
     * @param integer $idUser
     * @return boolean
     */
    public function addAddress(string $firstname, string $lastname, string $streetNumber, string $streetName, string $zipcode, string $city, int $idUser): bool
    {
        $sql ="INSERT INTO Addresses (firstname, lastname, street_number, street_name, zipcode, city) VALUES (?, ?, ?, ?, ?, ?)";
        if($this->request($sql, [$firstname, $lastname, $streetNumber, $streetName, $zipcode, $city])){
            $newAddressId = $this->request("SELECT MAX(id_address) as max FROM Addresses")->fetch();
            $sql="INSERT INTO Users_Addresses (id_user, id_address) VALUES (?, ?)";
            if($this->request($sql, [$idUser, $newAddressId->max])){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    /**
     * update an address in the database
     *
     * @param string $firstname
     * @param string $lastname
     * @param string $streetNumber
     * @param string $streetName
     * @param string $zipcode
     * @param string $city
     * @param integer $idAddress
     * @return boolean
     */
    public function updateAddress(string $firstname, string $lastname, string $streetNumber, string $streetName, string $zipcode, string $city, int $idAddress): bool
    {
        $sql = "UPDATE Addresses SET firstname = ?, lastname = ?, street_number = ?, street_name = ?, zipcode = ?, city = ? WHERE id_address = ?";
        if($this->request($sql, [$firstname, $lastname, $streetNumber, $streetName, $zipcode, $city, $idAddress])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * delete an address in the database
     *
     * @param integer $idAddress
     * @return boolean
     */
    public function deleteAddress(int $idAddress): bool
    {
        $sql="DELETE FROM Addresses WHERE id_address = ?";
        if($this->request($sql, [$idAddress])){
            return true;
        }else{
            return false;
        }
    }
}
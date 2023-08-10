<?php 
namespace App\Models;

class Addresses extends Model
{
    protected $id;
    protected $streetNumber;
    protected $streetName;
    protected $zipcode;
    protected $city;
    protected $type;

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
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
<?php 

namespace App\Models;

class Cart extends Model
{
    protected $id;
    protected $idUser;
    protected $createdAt;
    protected $updateAt;

    public function __construct()
    {
        $this->table = "Cart";
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
     * Get the value of idUser
     */ 
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */ 
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updateAt
     */ 
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * Set the value of updateAt
     *
     * @return  self
     */ 
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * function return the cart of a user.
     *
     * @param [type] $id
     * @return array
     */
    public function getUserCart($id): array
    {
        $sql = "SELECT p.name AS product_name,
            p.id_product AS id_product,
            p.price AS product_price,
            pic.filename AS picture_filename
            FROM Products p
            JOIN Cart_Products cp ON cp.id_product = p.id_product
            JOIN Cart c ON c.id_cart = cp.id_cart
            WHERE c.id_user = ?";

        return $this->request($sql, [$id])->fetchAll();
    }

    public function getCartNumberProduct(int $id):int
    {
        $sql ="SELECT count(cp.id_product) AS total_products
        FROM cart c,
        LEFT JOIN Cart_Products cp ON cp.id_cart = c.id_cart
        WHERE c.id_user = ?";
        return $this->request($sql,[$id])->fetch();
    }
}
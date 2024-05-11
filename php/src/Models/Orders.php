<?php 
namespace App\Models;

use PDO;
use App\Core\Db;

class Orders extends Model
{
    protected $id;
    protected $orderNumber;
    protected $createdAt;
    protected $idUser;

    public function __construct()
    {
        $this->table = "Orders";
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
     * Get the value of orderNumber
     */ 
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * Set the value of orderNumber
     *
     * @return  self
     */ 
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

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
     * create an order
     *
     * @param string $orderNumber
     * @param string $date
     * @param string $deliveryAddress
     * @param string $invoiceAddress
     * @param integer $idUser
     * @return boolean|string
     */
    public function createOrder(string $orderNumber,string $date, string $deliveryAddress, string $invoiceAddress, float $price, int $idUser): bool|string
    {
        $sql =" INSERT INTO Orders (order_number, created_at, delivery_address, invoice_address, total_price, id_user)
            VALUES (?, ?, ?, ?, ?, ?)";
        if($this->request($sql, [$orderNumber, $date, $deliveryAddress, $invoiceAddress, $price, $idUser])){
            return Db::getInstance()->lastInsertId();
        }else{
            return false;
        }
    }

    /**
     * add a product to an order
     *
     * @param integer $idProd
     * @param integer $quantity
     * @param integer $idOrder
     * @return void
     */
    public function addProductInOrder(int $idProd, int $quantity, int $idOrder):void
    {
        $sql = "INSERT INTO Orders_Products (id_product, quantity, id_order)
            VALUES (?, ?, ?)";
            $this->request($sql, [$idProd, $quantity, $idOrder]);
    }
}
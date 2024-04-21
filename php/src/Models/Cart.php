<?php 

namespace App\Models;

use DateTime;

class Cart extends Model
{
    protected $id;
    protected $idUser;
    protected $createdAt;

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
     * return the cart of an user.
     *
     * @param [type] $id
     * @return array
     */
    public function getUserCart($idUser): array
    {
        $sql = "SELECT p.name AS product_name,
            p.id_product AS id_product,
            p.price AS product_price,
            pic.filename AS picture_filename
            FROM Products p
            JOIN Cart_Products cp ON cp.id_product = p.id_product
            JOIN Cart c ON c.id_cart = cp.id_cart
            WHERE c.id_user = ?";

        return $this->request($sql, [$idUser])->fetchAll();
    }

    /**
     * return id and quantity of a product in a cart
     *
     * @param integer $idCart
     * @return array
     */
    public function getProductsIdFromCart(int $idCart): array
    {
        $sql="SELECT id_product, quantity FROM Cart_Products 
        WHERE id_cart = ?";
        return $this->request($sql, [$idCart])->fetchAll();
    }

    /**
     * get the id cart with the id of user
     *
     * @param integer $idUser
     * @return boolean
     */
    public function getIdCartFromIdUser(int $idUser):int
    {
        $sql="SELECT id_cart as id_cart 
        FROM Cart 
        WHERE id_user = ?";
        $result = $this->request($sql, [$idUser])->fetch();
        if($result){
            return $result->id_cart; 
        }else{
            $this->createUserCart($idUser);
            return $this->getIdCartFromIdUser($idUser);
        }
    }

    /**
     * create a cart for an user
     *
     * @param integer $idUser
     * @return void
     */
    public function createUserCart(int $idUser):void
    {
        $date = new DateTime();
        $formatedDate = $date->format('Y-m-d');
        $sql="INSERT INTO Cart (id_user, created_at) VALUES (?, ?)";
        $this->request($sql, [$idUser, $formatedDate]);
    }

    /**
     * verify if a product is in an user cart
     *
     * @param integer $idCart
     * @param integer $idProduct
     * @return boolean
     */
    public function isExistingProduct(int $idCart, int $idProduct): bool
    {
        $sql="SELECT id_product 
        FROM Cart_Products 
        WHERE id_cart = ? AND id_product = ?";
        $result = $this->request($sql, [$idCart, $idProduct])->fetch();
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * update quantity when the data go from cookie to cart in the database
     *
     * @param integer $idProduct
     * @param integer $idCart
     * @param integer $quantity
     * @return void
     */
    public function updateQuantity(int $idCart, int $idProduct, int $quantity ): void
    {
        $sql="SELECT quantity 
        FROM Cart_Products 
        WHERE id_product = ? AND id_cart = ?";
        $cartQuantity = $this->request($sql, [$idProduct, $idCart])->fetchAll();
        $quantity += $cartQuantity[0]->quantity;
        $sql="UPDATE Cart_Products SET quantity = ?
        WHERE id_product = ? AND id_cart = ?";
        $this->request($sql, [$quantity, $idProduct, $idCart]);
    }

    /**
     * add a product in the database cart from a cart's cookie
     *
     * @param integer $idCart
     * @param integer $idProduct
     * @param [type] $quantity
     * @return void
     */
    public function fromSessionToCart(int $idCart, int $idProduct,int $quantity):void
    {
        $sql="INSERT INTO Cart_Products (id_product, id_cart, quantity) VALUES (?, ?, ?)";
        $this->request($sql, [$idProduct, $idCart, $quantity]);
    }

    /**
     * add a product in an user cart
     *
     * @param integer $productId
     * @param integer $idUser
     * @return void
     */
    public function addProductInCart(int $productId, int $idUser):void
    {
        $idCart = $this->getIdCartFromIdUser($idUser);
        if($this->isExistingProduct($idCart, $productId )){
            $this->updateQuantity($idCart, $productId, 1);
        }else{
            $sql="INSERT INTO Cart_Products (id_product, id_cart, quantity) VALUES (?, ?, ?)";
            $this->request($sql, [$productId, $idCart, 1]);
        }
    }

    /**
     * delete a product in a cart
     *
     * @param integer $idCart
     * @param integer $productId
     * @return void
     */
    public function deleteProductInCart(int $idCart, int $productId):void
    {
        $sql="DELETE FROM Cart_Products WHERE id_product = ? AND id_cart = ?";
        $this->request($sql, [$productId, $idCart]);

    }

    /**
     * return the number of products in an user cart
     *
     * @param integer $idCart
     * @return integer
     */
    public function getCartNumberProduct(int $idCart):int
    {
        $quantity = 0;
        $products = $this->getProductsIdFromCart($idCart);
        foreach($products as $product){
            $q = $product->quantity;
            $quantity += $q;
        }
        return $quantity;
    }
}
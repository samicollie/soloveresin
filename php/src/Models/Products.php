<?php 

namespace App\Models;

class Products extends Model
{
    protected $id;
    protected $name;
    protected $description;
    protected $price;
    protected $maxQuantity;
    protected $announced;

    public function __construct()
    {
        $this->table = "Products";        
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
     * @param int $id
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     * @return  self
     */ 
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     * @param string  $description
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */ 
    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of maxQuantity
     */ 
    public function getMaxQuantity()
    {
        return $this->maxQuantity;
    }

    /**
     * Set the value of maxQuantity
     *
     * @return  self
     */ 
    public function setMaxQuantity($maxQuantity)
    {
        $this->maxQuantity = $maxQuantity;

        return $this;
    }

    /**
     * get all the products in the database with picture and rating.
     *
     * @return array
     */
    public function getAllProducts(): array
    {
        $sql="SELECT
        p.id_product AS id_product,
        p.name AS product_name,
        p.price AS product_price,
        p.announced AS announced,
        pic.filename AS picture_filename,
        rating_stats.average_rating,
        rating_stats.rating_count
    FROM Products p
    LEFT JOIN Pictures pic ON p.id_product = pic.id_product
    LEFT JOIN (
        SELECT
            c.id_product,
            AVG(c.rating) AS average_rating,
            COUNT(c.id_comment) AS rating_count
        FROM Comments c
        GROUP BY c.id_product
    ) AS rating_stats ON p.id_product = rating_stats.id_product
        WHERE p.exhausted = 0";
        return $this->request($sql)->fetchAll();
    }

    /**
     * get one Product with pictures and average rating
     *
     * @param integer $id id_product
     * @return object
     */
    public function getOneProduct(int $id): object
    {
        $sql = "SELECT
        p.id_product,
        p.name AS product_name,
        FORMAT(p.price, 2) AS product_price,
        p.description AS product_description,
        pic.filename AS picture_filename,
        pic.id_picture AS id_picture,
        rating_stats.average_rating AS average_rating,
        rating_stats.rating_count AS count_rating
            FROM Products p
            LEFT JOIN Pictures pic ON p.id_product = pic.id_product
            LEFT JOIN (
                SELECT
                    id_product,
                    AVG(rating) AS average_rating,
                    COUNT(id_comment) AS rating_count
                FROM Comments
                GROUP BY id_product
            ) AS rating_stats ON p.id_product = rating_stats.id_product
            WHERE p.id_product = ?";

        return  $this->request($sql,[$id])->fetchObject();
    }

    /**
     * get the max quantity of a product
     *
     * @param integer $idProduct
     * @return integer
     */
    public function getMaxQuantityProduct(int $idProduct): int
    {
        $sql = "SELECT max_quantity as max FROM Products where id_product = ?";
        return $this->request($sql, [$idProduct])->fetch()->max;
    }


    /**
     * return if a product is exhausted
     *
     * @param integer $idProduct
     * @return boolean
     */
    public function isProductExhausted(int $idProduct): bool
    {
        $sql = "SELECT exhausted FROM Products
        WHERE id_product = ?";
        $result = $this->request($sql, [$idProduct])->fetch()->exhausted;
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * update the quantity of a product in database
     *
     * @param integer $idProduct
     * @param integer $quantity
     * @return void
     */
    public function updateProductQuantity(int $idProduct, int $quantity): void
    {
        $maxQuantity = $this->getMaxQuantityProduct($idProduct);
        $maxQuantity -= $quantity;
        if($maxQuantity === 0){
            $exhauted = 1;
        }else{
            $exhauted = 0;
        }
        $sql = "UPDATE Products SET max_quantity = ?, exhausted = ? WHERE id_product = ?";
        $this->request($sql, [$maxQuantity, $exhauted, $idProduct]);
    }

    /**
     * return the last id from Products table
     *
     * @return integer
     */
    public function getLastId(): int
    {
        $sql = "SELECT MAX(id_product) as id FROM Products";
        $result = $this->request($sql)->fetch();
        return $result->id;
    }

    /**
     * add a product in the database
     *
     * @param string $name
     * @param string $price
     * @param string $description
     * @return void
     */
    public function addProduct(string $name, float $price, string $description): bool
    {
        $sql = "INSERT INTO Products (name, description, price) VALUES (?, ?, ?)";
        if($this->request($sql, [$name, $description, $price])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * update informations from a product
     *
     * @param string $name
     * @param string $description
     * @param string $price
     * @param integer $productId
     * @return void
     */
    public function updateProduct(string $name, string $description, float $price, int $productId): bool
    {
        $sql="UPDATE Products SET name = ?, description = ?, price =  ? WHERE id_product = ?";
        if($this->request($sql, [$name, $description , $price, $productId])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * delete a product in the database
     *
     * @param integer $idProd
     * @return boolean
     */
    public function deleteProduct(int $idProd):bool
    {
        $sql = "DELETE FROM Products WHERE id_product = ?";
        if($this->request($sql, [$idProd])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * get the announced products
     *
     * @return array
     */
    public function getAnnouncedProducts(): array
    {
        $sql="SELECT
        p.id_product AS id_product,
        pic.filename AS picture_filename
            FROM Products p
                LEFT JOIN Pictures pic ON p.id_product = pic.id_product
            WHERE p.announced = 1";
    return $this->request($sql)->fetchAll();
    }

    /**
     * search some products in database from criteria
     *
     * @param string $search
     * @return array
     */
    public function searchProducts(string $search): array
    {
        $sql = "SELECT
        p.id_product AS id_product,
        p.name AS product_name,
        FORMAT(p.price,2) AS product_price,
        pic.filename AS picture_filename,
        rating_stats.average_rating,
        rating_stats.rating_count
        FROM Products p
        LEFT JOIN Pictures pic ON p.id_product = pic.id_product
        LEFT JOIN (
            SELECT
                c.id_product,
                AVG(c.rating) AS average_rating,
                COUNT(c.id_comment) AS rating_count
            FROM Comments c
            GROUP BY c.id_product
        ) AS rating_stats ON p.id_product = rating_stats.id_product
        WHERE p.name LIKE ?";
        $criteria = trim(ucfirst($search)) . '%';
        return $this->request($sql, [$criteria])->fetchAll();

    }

    public function updateToAnnounced(int $id, int $announced):void
    {
        $sql = "UPDATE Products SET announced = ? WHERE id_product = ?";
        $this->request($sql, [$announced, $id]);
    }

    
}
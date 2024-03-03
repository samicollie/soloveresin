<?php 

namespace App\Models;

class Products extends Model
{
    protected $id;
    protected $name;
    protected $description;
    protected $pictures = [];
    protected $rating = [0, 0];
    protected $price;

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
     * @return  self
     */ 
    public function setId($id)
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
     * @return  self
     */ 
    public function setName($name)
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
     *
     * @return  self
     */ 
    public function setDescription($description)
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
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * get all the products in the database with their pictures and rating.
     *
     * @return array
     */
    public function getAllProducts(): array
    {
        $sql="SELECT
        p.id_product AS id_product,
        p.name AS product_name,
        p.price AS product_price,
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
    ) AS rating_stats ON p.id_product = rating_stats.id_product";
        return $this->request($sql)->fetchAll();
    }

    /**
     * get one Product with pictures and average rating
     *
     * @param integer $id id_product
     * @return array
     */
    public function getOneProduct(int $id): array
    {
        $sql = "SELECT
        p.id_product,
        p.name AS product_name,
        p.price AS product_price,
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

        return  $this->request($sql,[$id])->fetchAll();
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
    public function addProduct(string $name, string $price, string $description): bool
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
    public function updateProduct(string $name, string $description, string $price, int $productId): bool
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
     * @return void
     */
    public function deleteProduct(int $idProd):void
    {
        $sql = "DELETE FROM Products WHERE id_product = ?";
        $this->request($sql, [$idProd]);
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
        p.price AS product_price,
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

}
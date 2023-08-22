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
     * get all the products in the database with their pictures en rating.
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
    JOIN Pictures pic ON p.id_product = pic.id_product
<<<<<<< HEAD
    LEFT JOIN (
=======
    JOIN (
>>>>>>> 9f0f5ab209f2f37e2ccbc604b3c591cea2c4323e
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
     * function which get one Product with pictures and average rating
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
<<<<<<< HEAD
        p.description AS product_description,
=======
>>>>>>> 9f0f5ab209f2f37e2ccbc604b3c591cea2c4323e
        pic.filename AS picture_filename,
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

}
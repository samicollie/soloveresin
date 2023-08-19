<?php 
namespace App\Models;

use App\Core\Db;
use App\Models\Users;

class Comments extends Model
{
    protected $id;
    protected $title;
    protected $content;
    protected $rating;
    protected $idProduct;
    protected $idUser;

    public function __construct()
    {
        $this->table = "Comments";
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
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of content
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */ 
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of rating
     */ 
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set the value of rating
     *
     * @return  self
     */ 
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get the value of idProduct
     */ 
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set the value of idProduct
     *
     * @return  self
     */ 
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

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
     * function which return all comments for one product with fullname of user.
     *
     * @param integer $id id_product
     * @return array $comments
     */
    public function getComments(int $id): array
    {
        $sql = "SELECT c.title AS comment_title,
        c.content AS comment_content,
        u.firstname AS user_firstname,
        u.lastname AS user_lastname,
        c.rating AS comment_rating
            FROM Comments c
            JOIN Users u ON u.id_user = c.id_user
            WHERE c.id_product = ?";
        return $this->request($sql, [$id])->fetchAll();
    }
}
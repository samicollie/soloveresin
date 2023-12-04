<?php 
namespace App\Models;

class Pictures extends Model
{
    protected $id;
    protected $filename;
    protected $path;
    protected $size;
    protected $createdAt;
    protected $updatedAt;
    protected $idProduct;
    

    public function __construct()
    {
        $this->table ="Pictures";
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
     * Get the value of filename
     */ 
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @return  self
     */ 
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @return  self
     */ 
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of size
     */ 
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @return  self
     */ 
    public function setSize($size)
    {
        $this->size = $size;

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
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

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
     * verify if a picture exists for a product
     *
     * @param integer $productId
     * @return boolean
     */
    public function isPictureExist(int $productId): bool
    {
        $sql = "SELECT id_picture  FROM Pictures WHERE id_product = ?";
        $result = $this->request($sql, [$productId])->fetch();
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * add a picture in the database
     *
     * @param string $filename
     * @param string $path
     * @param integer $size
     * @param string $date
     * @param integer $productId
     * @return void
     */
    public function addPicture(string $filename, string $path, int $size, string $date, int $productId): bool
    {
        $sql= "INSERT INTO Pictures (filename, path, size, created_at, id_product) VALUES (?, ?, ?, ?, ?)";
        if($this->request($sql, [$filename, $path, $size, $date, $productId])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * update a picture in the database
     *
     * @param string $filename
     * @param integer $size
     * @param string $date
     * @param integer $productId
     * @return void
     */
    public function updatePicture( string $filename, int $size, string $date, $productId): bool
    {
        $sql="SELECT filename FROM Pictures WHERE id_product = ?";
        $picture = $this->request($sql, [$productId])->fetch();
        unlink(ROOT . '/public/assets/images/' . $picture->filename);
        $sql="UPDATE Pictures SET filename = ?, size = ?, updated_at = ? WHERE id_product = ?";
        if($this->request($sql, [$filename, $size, $date, $productId])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * delete a picture
     *
     * @param integer $pictureId
     * @return void
     */
    public function deletePicture(int $pictureId):void
    {
        $sql = "SELECT filename FROM Pictures WHERE id_picture = ?";
        $picture = $this->request($sql, [$pictureId])->fetch();
        unlink(ROOT . '/public/assets/images/' . $picture->filename);
        $sql="DELETE FROM Pictures WHERE id_picture = ?";
        $this->request($sql, [$pictureId]);
    }
}
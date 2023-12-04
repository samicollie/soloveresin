<?php 
namespace App\Models;

class Categories extends Model
{
    protected $id;
    protected $name;
    
    public function __construct()
    {
        $this->table = "Categories";
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
     * get all categorie from the database
     *
     * @return array
     */
    public function getAllCategories(): array
    {
        $sql= "SELECT id_category, name FROM Categories";
        return $this->request($sql)->fetchAll();
    }

    /**
     * get one category from the database
     *
     * @param integer $id
     * @return void
     */
    public function getOneCategory(int $id)
    {
        $sql = "SELECT id_category, name FROM Categories WHERE id_category = ?";
        return $this->request($sql, [$id])->fetch();
    }

    /**
     * add a new category in the database
     *
     * @param string $name
     * @return bool
     */
    public function addCategory(string $name) : bool
    {
        $sql="INSERT INTO Categories (name) VALUES (?) ";
        if($this->request($sql, [$name])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * update a category in the database
     *
     * @param integer $id
     * @param string $name
     * @return boolean
     */
    public function updateCategory(int $id, string $name): bool
    {
        $sql = "UPDATE Categories SET name = ? WHERE id_category = ?";
        if($this->request($sql, [$name, $id])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * delete a category in the database
     *
     * @param integer $id
     * @return void
     */
    public function deleteCategory(int $id)
    {
        $sql = "DELETE FROM Categories WHERE id_category = ?";
        $this->request($sql, [$id]);
    }

}
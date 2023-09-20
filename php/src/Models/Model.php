<?php 
namespace App\Models;

use App\Core\Db;


class Model extends Db
{
    //name of the table in the database
    protected $table;

    //instance de Db
    private $db;

   
    public function findAll()
    {
        $statement = $this->request("SELECT * FROM $this->table");
        return $statement->fetchAll();
    }

    
    public function findBy(array $criteria)
    {
        $fields = [];
        $values = [];

        //explode ths criteria's array
        foreach($criteria as $field => $value){
            $fields[] = "$field = ?";
            $values[] = $value;
        }

        //transform fields array in a string
        $listFields = implode(' AND ', $fields);

        //execute the request
        return $this->request("SELECT * FROM $this->table WHERE $listFields", $values)->fetchAll();
    }

    public function find(int $id)
    {
        return $this->request("SELECT * FROM $this->table WHERE id_" . lcfirst($this->table) . " = $id")->fetch();
    }

    public function create(Model $model)
    {
        $fields = [];
        $options = [];
        $values = [];

        //explode the model and prepare the request synthax
        foreach($model as $field => $value){
            if($value !== null && $field != 'db' && $field != 'table'){
                $fields[] = $field;
                $options[] = "?";
                $values[] = $value;
            }
        }
            //transform fields and options array in a string to insert it in the request
            $listFields = implode(', ', $fields);
            $listOptions = implode(', ', $options);
            
            return $this->request("INSERT INTO $this->table ($listFields) VALUES ($listOptions)", $values);
    }

    public function update(int $id,Model $model)
    {
        $fields = [];
        $values = [];

        //explode the model and prepare the request synthax
        foreach($model as $field => $value){
            if($value !== null && $field != 'db' && $field != 'table'){
                $fields[] = "$field = ?";
                $values[] = $value;
            }
        }
            $values[] = $id;
            //transform fields and options array in a string to insert it in the request
            $listFields = implode(', ', $fields);
            
            return $this->request("UPDATE $this->table SET $listFields WHERE id_". lcfirst($this->table) ."= ?" , $values);
    }

    public function delete(int $id){
        return $this->request("DELETE FROM $this->table WHERE id_" . lcfirst($this->table) . "= ?", [$id]);
    }


    public function request(string $sql, array $attributes = null)
    {
        //get the instance Db
        $this->db = Db::getInstance();

        //verify if we have attributes
        if($attributes !== null){
            //prepared request 
            $statement = $this->db->prepare($sql);
            $statement->execute($attributes);
            return $statement;
        }else{
            //simple request
            return $this->db->query($sql);
        }
    }

    public function hydrate(array $data)
    {
        foreach($data as $key => $value){
            //get the potential setter matches with the key
            $setter = 'set'. ucfirst($key);

            //verify if the setter existe in the class
            if(method_exists($this, $setter)){
                $this->$setter($value);
            }
        }
        return $this;
    }
}
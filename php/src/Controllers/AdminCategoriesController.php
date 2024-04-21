<?php 

namespace App\Controllers;

use App\Models\Categories;
use App\Controllers\Traits\Validatortrait;

class AdminCategoriesController extends Controller{
    use Validatortrait;

    /**
     * display a formular to add a category
     *
     * @return void
     */
    public function indexAddCategory()
    {
        $this->isAuthorizedForAdminPage();
        $this->render('admin/addCategoryFormular', 'Ajouter une catégorie');
    }

    /**
     * add a category in the database
     *
     * @return void
     */
    public function addCategory()
    {
        $this->isAuthorizedForAdminPage();
        $fields = $this->cleanFields($_POST);
        $errorMessage = $this->validateFields($fields);
        $name = $fields['category'] ?? '';
        if(empty($name)){
            $errorMessage['blank'] = "Veuillez saisir le nom de la catégorie.";
        }
        if(!empty($errorMessage)){
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $categoryModel = new Categories;
        if(!$categoryModel->addCategory($name)){
            $errorMessage['request'] = "Une erreur s'est produite lors de l'ajout de la catégorie.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $successMessage = "La catégorie a été ajoutée avec succés.";
        $this->sendJSONResponse(['success' => true, 'message' => $successMessage]);
    }

    /**
     * display the list of all categories to modify or delete one
     *
     * @return void
     */
    public function listModifyCategories()
    {
        $this->isAuthorizedForAdminPage();
        $categoryModel = new Categories;
        $categories = $categoryModel->getAllCategories();
        $this->render('admin/listModifyCategories', 'Liste des catégories', ['categories' => $categories]);

}

    /**
     * display a formular to modify a category
     *
     * @param array $param
     * @return void
     */
    public function modifyCategory(array $param)
    {
        $this->isAuthorizedForAdminPage();
        $idCategory = intval($param[0]);
        $categoryModel = new Categories;
        $category = $categoryModel->getOneCategory($idCategory);
        $this->render('admin/modifyCategoryFormular', 'Modifier une catégorie', ['category' => $category]);
    }

    /**
     * update a category in the database
     *
     * @return void
     */
    public function updateCategory()
    {
        $this->isAuthorizedForAdminPage();
        $fields = $this->cleanFields($_POST);
        $errorMessage = $this->validateFields($fields);
        $name = $fields['category'] ?? '';
        $idCategory = $fields['id_category'] ?? '';
        if(empty($name)){
            $errorMessage['blank'] = "Veuillez saisir le nom de la catégorie.";
        }
        if(!empty($errorMessage)){
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $categoryModel = new Categories;
        
        if(!$categoryModel->updateCategory($idCategory, $name)){
            $errorMessage['categoryName'] = "Une erreur s'est produite lors de la modification.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $successMessage = "La modification a été réalisée avec succés";
        $this->sendJSONResponse(['success' => true, 'message' => $successMessage]);
    }

    /**
     * delete a category in the database
     *
     * @return void
     */
    public function deleteCategory()
    {
        $this->isAuthorizedForAdminPage();
        if(isset($_POST['id_category'])){
            $idCategory = htmlspecialchars(strip_tags($_POST['id_category']));
            $categoryModel = new Categories;
            $categoryModel->deleteCategory($idCategory);
            header("location: /admin/categories/list");
        }
    }
}
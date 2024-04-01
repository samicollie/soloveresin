<?php 

namespace App\Controllers;

use App\Models\Categories;
use App\Models\Pictures;
use App\Models\Products;
use App\Controllers\Traits\Validatortrait;
use DateTime;

class AdminController extends Controller{
    use Validatortrait;

    /**
     * display the admin dashboard
     *
     * @return void
     */
    public function index()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                $this->render('admin/dashboard', 'Tableau de bord administrateur');
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }

    }

    /**
     * display the formular to add a product
     *
     * @return void
     */
    public function indexAddProduct()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                $this->render('admin/addProductFormular', 'Ajouter un article');
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    /**
     * add a product in the database
     *
     * @return void
     */
    public function addProduct()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                if(isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description'])){
                    if(!empty($_POST['name']) && !empty($_POST['price'])){
                        if(is_uploaded_file($_FILES['image']['tmp_name'])){
                            $isFile = true;
                            if(!$this->isJPEG($_FILES['image']['tmp_name'])){
                                $errorMessage['imageType'] = "L'image doit être au format JPEG.";
                            }
                            if(!$this->isImageSizeValid($_FILES['image']['tmp_name'])){
                                $errorMessage['imageSize'] = "L'image est trop grande.";
                            }
                        }else{
                            $isFile = false;
                        }
                        if(!$this->validateStringWithOnlyLettersAndDigits($_POST['name'])){
                            $errorMessage['productName'] = "Le nom de l'article est invalide.";
                        }
                        // if(!$this->validatePrice(($_POST['name']))){
                        //     $errorMessage['price'] = "Le prix n'est pas valide.";
                        // }
                        if(!empty($errorMessage)){
                            $response = ['errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit;
                        }else{
                            $name = htmlspecialchars(strip_tags($_POST['name']));
                            $price = floatval(htmlspecialchars(strip_tags($_POST['price'])));
                            $description = htmlspecialchars(strip_tags($_POST['description']));
                            $productModel = new Products;
                            if($productModel->addProduct($name, $price, $description)){
                                $productId = $productModel->getLastId();
                                $isPictureAdd = true;
                                if($isFile){
                                    $uploadFileName = $_FILES['image']['name'];
                                    $sizePicture = $_FILES['image']['size'];
                                    move_uploaded_file($_FILES['image']['tmp_name'], ROOT . '/public/assets/images/' . $uploadFileName);
                                    $date = new DateTime();
                                    $date = $date->format('Y-m-d');
                                    $pictureModel = new Pictures;
                                    $isPictureAdd = $pictureModel->addPicture($uploadFileName, '/images/', $sizePicture, $date, $productId );
                                }
                                if($isPictureAdd){
                                    $successMessage = "L'ajout a été fait avec succés.";
                                    $response = ['success' => true, 'successMessage' => $successMessage];
                                    echo json_encode($response);
                                    exit;
                                }else{
                                    $errorMessage['request'] = "Une erreur s'est produite avec l'ajout de l'image.";
                                    $response = ['errorMessage' => $errorMessage];
                                    echo json_encode($response);
                                    exit;
                                }
                            }else{
                                $errorMessage['request'] = "Une erreur s'est produite avec l'ajout du produit.";
                                $response = ['errorMessage' => $errorMessage];
                                echo json_encode($response);
                                exit;
                            }
                        }
                    }else{
                        $errorMessage['blank'] = "Veuillez remplir tous les champs.";
                        $response = ['errorMessage' => $errorMessage];
                        echo json_encode($response);
                        exit;
                    }
                }else{
                    echo json_encode('ok');
                    //header("location: /admin/addProductFormular");
                }
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    /**
     * display the list of all products to allaw modify them
     *
     * @return void
     */
    public function listModifyProducts()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                $productModel = new Products;
                $products = $productModel->getAllProducts();
                $this->render('admin/listModifyProducts', 'Liste des articles', ['products' => $products]);
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    /**
     * display the modify formular on a product
     *
     * @param array $param
     * @return void
     */
    public function modifyProduct(array $param)
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                $productId = intval($param[0]);
                $productModel = new Products;
                $product = $productModel->getOneProduct($productId)[0];
                $this->render('admin/modifyProductFormular', 'Modifier un article', ['product' => $product]);
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }

    }

    /**
     * delete a picture from a product
     *
     * @param array $param
     * @return void
     */
    public function deletePicture(array $param)
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                $pictureId = intval($param[0]);
                $productId = intval($param[1]);
                $pictureModel = new Pictures;
                $pictureModel->deletePicture($pictureId);
                header("location: /admin/products/modify/" . strval($productId));
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }

    }

    /**
     * update a product data and/or picture
     *
     * @return void
     */
    public function updateProduct()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                if(isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description']) && isset($_POST['id_product'])){
                    $productId = htmlspecialchars(strip_tags($_POST['id_product']));
                    if(!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['id_product'])){
                        if(is_uploaded_file($_FILES['image']['tmp_name'])){
                            $isFile = true;
                            if(!$this->isJPEG($_FILES['image']['tmp_name'])){
                                $errorMessage['imageType'] = "L'image doit être au format JPEG.";
                            }
                            if(!$this->isImageSizeValid($_FILES['image']['tmp_name'])){
                                $errorMessage['imageSize'] = "L'image est trop grande.";
                            }
                        }else{
                            $isFile = false;
                        }
                        if(!$this->validateStringWithOnlyLettersAndDigits($_POST['name'])){
                            $errorMessage['productName'] = "Le nom de l'article est invalide";
                        }
                        // if(!$this->validatePrice(($_POST['name']))){
                        //     $errorMessage['productName'] = "Le prix n'est pas valide.";
                        // }

                        if(!empty($errorMessage)){
                            $response = ['errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit;
                        }else{
                            $name = htmlspecialchars(strip_tags($_POST['name']));
                            $price = floatval(htmlspecialchars(strip_tags($_POST['price'])));
                            $description = htmlspecialchars(strip_tags($_POST['description']));
                            $isPictureInDatabase = true;
                            if($isFile){
                                $uploadFileName= $_FILES['image']['name'];
                                $sizePicture = $_FILES['image']['size'];
                                move_uploaded_file($_FILES['image']['tmp_name'], ROOT . '/public/assets/images/' . $uploadFileName);
                                $pictureModel = new Pictures;
                                $isExistingPicture = $pictureModel->isPictureExist($productId);
                                $date = new DateTime();
                                $date = $date->format('Y-m-d');
                                if($isExistingPicture){
                                    $isPictureInDatabase = $pictureModel->updatePicture($uploadFileName, $sizePicture, $date, $productId);
                                }else{
                                    $isPictureInDatabase = $pictureModel->addPicture($uploadFileName, '/images/', $sizePicture, $date, $productId);
                                }
                            }
                            $productModel = new Products;
                            if($productModel->updateProduct($name, $description,$price, $productId)){
                                if($isPictureInDatabase){
                                    $successMessage = "La modification a été réalisée avec succés.";
                                    $response = ['success' => true, 'successMessage' => $successMessage];
                                    echo json_encode($response);
                                    exit;
                                }else{
                                    $errorMessage['request'] = "Une erreur s'est produite avec l'image.";
                                    $response = ['errorMessage' => $errorMessage];
                                    echo json_encode($response);
                                    exit;
                                }
                            }else{
                                $errorMessage['request'] = "Une erreur s'est produite avec la modification de l'article.";
                                $response = ['errorMessage' => $errorMessage];
                                echo json_encode($response);
                                exit;
                            }
                        }
                    }else{
                        $errorMessage['blank'] = "Veuillez remplir tous les champs.";
                        $response = ['errorMessage' => $errorMessage];
                        echo json_encode($response);
                        exit;
                    }
                }
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    /**
     * delete a product in the database
     *
     * @return void
     */
    public function deleteProduct()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                if(isset($_POST['id_product'])){
                    if(!empty($_POST['id_product'])){
                        $idProd = htmlspecialchars(strip_tags($_POST['id_product']));
                        $productModel = new Products;
                        $productModel->deleteProduct($idProd);
                        $products = $productModel->getAllProducts();
                        $this->render('admin/listModifyProducts', 'Liste des articles', ['products' => $products]);
                    }
                }
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    /**
     * display a formular to add a category
     *
     * @return void
     */
    public function indexAddCategory()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                $this->render('admin/addCategoryFormular', 'Ajouter une catégorie');
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    /**
     * add a category in the database
     *
     * @return void
     */
    public function addCategory()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                if(isset($_POST['name'])){
                    if(!empty($_POST['name'])){
                        if(!$this->validateStringWithOnlyLettersAndDigits($_POST['name'])){
                            $errorMessage['categoryName'] = "Le nom de catégorie est invalide.";
                        }
                        if(!empty($errorMessage)){
                            $response = ['errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit;
                        }
                        $name = htmlspecialchars(strip_tags($_POST['name']));
                        $categoryModel = new Categories;
                        $isCreated = $categoryModel->addCategory($name);
                        if($isCreated){
                            $successMessage = "La catégorie a été ajoutée avec succés.";
                            $response = ['success' => true, 'successMessage' => $successMessage];
                            echo json_encode($response);
                            exit;
                        }else{
                            $errorMessage['request'] = "Une erreur s'est produite lors de l'ajout de la catégorie.";
                            $response = ['errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit;
                        }
                    }else{
                        $errorMessage['blank'] = "Veuillez saisir le nom de la catégorie.";
                        $response = ['errorMessage' => $errorMessage];
                        echo json_encode($response);
                        exit;
                    }
                }
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    /**
     * display the list of all categories to modify or delete one
     *
     * @return void
     */
    public function listModifyCategories()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                $categoryModel = new Categories;
                $categories = $categoryModel->getAllCategories();
                $this->render('admin/listModifyCategories', 'Liste des catégories', ['categories' => $categories]);
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    /**
     * display a formular to modify a category
     *
     * @param array $param
     * @return void
     */
    public function modifyCategory(array $param)
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                $idCategory = intval($param[0]);
                $categoryModel = new Categories;
                $category = $categoryModel->getOneCategory($idCategory);
                $this->render('admin/modifyCategoryFormular', 'Modifier une catégorie', ['category' => $category]);
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    /**
     * update a category in the database
     *
     * @return void
     */
    public function updateCategory()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                if(isset($_POST['id_category']) && isset($_POST['name'])){
                    $idCategory = htmlspecialchars(strip_tags($_POST['id_category']));
                    if(!empty($_POST['name'])){
                        if(!$this->validateStringWithOnlyLettersAndDigits($_POST['name'])){
                            $errorMessage['categoryName'] = "Le nom de catégorie est invalide.";
                        }
                        if(!empty($errorMessage)){
                            $response = ['errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit;
                        }else{
                            $name = htmlspecialchars(strip_tags($_POST['name']));
                            $categoryModel = new Categories;
                            $isUpdated = $categoryModel->updateCategory($idCategory, $name);
                            if($isUpdated){
                                $successMessage = "La modification a été réalisée avec succés";
                                $response = ['success' => true, 'successMessage' => $successMessage];
                                echo json_encode($response);
                                exit;
                            }else{
                                $errorMessage['categoryName'] = "Une erreur s'est produite lors de la modification.";
                                $response = ['errorMessage' => $errorMessage];
                                echo json_encode($response);
                                exit;
                            }
                        }
                    }else{
                        $errorMessage['blank'] = "Veuillez saisir le nom de la catégorie.";
                        $response = ['errorMessage' => $errorMessage];
                        echo json_encode($response);
                        exit;
                    }
                }
            }
        }
    }

    /**
     * delete a category in the database
     *
     * @return void
     */
    public function deleteCategory()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                if(isset($_POST['id_category'])){
                    $idCategory = htmlspecialchars(strip_tags($_POST['id_category']));
                    $categoryModel = new Categories;
                    $categoryModel->deleteCategory($idCategory);
                    header("location: /admin/categories/list");
                }
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }

    public function searchProducts()
    {
        if($this->isLoggedIn){
            if($this->userRole === '["ROLE_ADMIN"]'){
                if(isset($_POST['search'])){
                    if(!empty($_POST['search'])){
                        $search = htmlspecialchars(strip_tags(urldecode($_POST['search'])));
                        $productModel = new Products;
                        $products = $productModel->searchProducts($search);
                        $this->renderPartial('admin/searchProducts', ['products' => $products]);
                    }else{
                        $productModel = new Products;
                        $products = $productModel->getAllProducts();
                        $this->renderPartial('admin/searchProducts', ['products' => $products]);
                    }
                }
            }else{
                header("location: /profile");
            }
        }else{
            header("location: /login");
        }
    }
}
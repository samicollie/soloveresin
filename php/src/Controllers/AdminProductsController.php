<?php 

namespace App\Controllers;

use DateTime;
use App\Models\Pictures;
use App\Models\Products;
use App\Controllers\Traits\Validatortrait;


class AdminProductsController extends Controller
{
    use Validatortrait;
    
    /**
     * display the formular to add a product
     *
     * @return void
     */
    public function indexAddProduct()
    {
        $this->isAuthorizedForAdminPage();
        $this->render('admin/addProductFormular', 'Ajouter un article');
        
    }

    /**
     * add a product in the database
     *
     * @return void
     */
    public function addProduct()
    {
        $this->isAuthorizedForAdminPage();
        $fields = $this->cleanFields($_POST);
        $uploadedFile = $this->cleanUploadedImage($_FILES);
        $errorMessage = $this->validateFields($fields);
        $errorMessage += $this->validateFields($_FILES);
        $name = $fields['name'] ?? '';
        $price = floatval($fields['price']) ?? '';
        $description = $fields['description'] ?? '';
        if(empty($name) || empty($price)){
            $errorMessage['blank'] = "Veuillez remplir tous les champs.";
        }
        
        if(!empty($errorMessage)){
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
            
        $productModel = new Products;
        if(!$productModel->addProduct($name, $price, $description)){
            $errorMessage['request'] = "Une erreur s'est produite avec l'ajout du produit.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $productId = $productModel->getLastId();
        if(is_uploaded_file($_FILES['image']['tmp_name'])){
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT . '/public/assets/images/' . $uploadedFile['imageName']);
            $date = new DateTime();
            $date = $date->format('Y-m-d');
            $pictureModel = new Pictures;
            if(!$pictureModel->addPicture($uploadedFile['imageName'], '/images/', $uploadedFile['sizeImage'], $date, $productId )){
                $errorMessage['request'] = "Une erreur s'est produite avec l'ajout de l'image.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
        }
        $successMessage = "L'ajout a été fait avec succés.";
        $this->sendJSONResponse(['success' => true, 'message' => $successMessage]);    
    }

    /**
     * display the list of all products to allaw modify them
     *
     * @return void
     */
    public function listModifyProducts()
    {
        $this->isAuthorizedForAdminPage();
        $productModel = new Products;
        $products = $productModel->getAllProducts();
        $this->render('admin/listModifyProducts', 'Liste des articles', ['products' => $products]);
    }

    /**
     * display the modify formular on a product
     *
     * @param array $param
     * @return void
     */
    public function modifyProduct(array $param)
    {
        $this->isAuthorizedForAdminPage();
        $productId = intval($param[0]);
        $productModel = new Products;
        $product = $productModel->getOneProduct($productId);
        $this->render('admin/modifyProductFormular', 'Modifier un article', ['product' => $product]);
    }

    /**
     * delete a picture from a product
     *
     * @param array $param
     * @return void
     */
    public function deletePicture(array $param)
    {
        $this->isAuthorizedForAdminPage();
        $pictureId = intval($param[0]);
        $productId = intval($param[1]);
        $pictureModel = new Pictures;
        $pictureName = $pictureModel->getPictureNameWithIdProduct($productId);
        if($pictureModel->deletePicture($pictureId)){
            if(file_exists(ROOT . '/public/assets/images/' . $pictureName)){
                unlink(ROOT . '/public/assets/images/' . $pictureName);
            }
        }
        header("location: /admin/products/modify/" . strval($productId));
    }

    /**
     * update a product data and/or picture
     *
     * @return void
     */
    public function updateProduct()
    {
        $this->isAuthorizedForAdminPage();
        $fields = $this->cleanFields($_POST);
        $uploadedFile = $this->cleanUploadedImage($_FILES);
        $errorMessage = $this->validateFields($fields);
        $errorMessage += $this->validateFields($_FILES);
        $productId = $fields['id_product'] ?? '';
        $name = $fields['name'] ?? '';
        $price = floatval($fields['price']);
        $description = $fields['description'];
        if(empty($name) || empty($price) || empty($productId)){
            $errorMessage['blank'] = "Veuillez remplir tous les champs.";
        }
        if(!empty($errorMessage)){
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $productModel = new Products;
        if(!$productModel->updateProduct($name, $description,$price, $productId)){
            $errorMessage['request'] = "Une erreur s'est produite avec la modification de l'article.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        if(is_uploaded_file($_FILES['image']['tmp_name'])){
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT . '/public/assets/images/' . $uploadedFile['imageName']);
            $pictureModel = new Pictures;
            $isExistingPicture = $pictureModel->isPictureExist($productId);
            $date = new DateTime();
            $date = $date->format('Y-m-d');
            if($isExistingPicture){
                if(!$pictureModel->updatePicture($uploadedFile['imageName'], $uploadedFile['sizeImage'], $date, $productId)){
                    $errorMessage['request'] = "Une erreur s'est produite avec l'image.";
                    $this->sendJSONResponse(['errorMessage' => $errorMessage]);
                }
            }else{
                if(!$pictureModel->addPicture($uploadedFile['imageName'], '/images/', $uploadedFile['sizeImage'], $date, $productId)){
                    $errorMessage['request'] = "Une erreur s'est produite avec l'image.";
                    $this->sendJSONResponse(['errorMessage' => $errorMessage]);
                }
            }
        }
        $successMessage = "La modification a été réalisée avec succés.";
        $this->sendJSONResponse(['success' => true, 'message' => $successMessage]);        
    }

    /**
     * delete a product in the database
     *
     * @return void
     */
    public function deleteProduct()
    {
        $this->isAuthorizedForAdminPage();
        if(isset($_POST['id_product'])){
            if(!empty($_POST['id_product'])){
                $idProd = htmlspecialchars(strip_tags($_POST['id_product']));
                $productModel = new Products;
                $pictureModel = new Pictures;
                $pictureName = $pictureModel->getPictureNameWithIdProduct($idProd);
                if($productModel->deleteProduct($idProd)){
                    if(file_exists(ROOT . '/public/assets/images/' . $pictureName)){
                        unlink(ROOT . '/public/assets/images/' . $pictureName);
                    }
                }
                $products = $productModel->getAllProducts();
                $this->render('admin/listModifyProducts', 'Liste des articles', ['products' => $products]);
            }
        }
    }

    /**
     * search products in the products list
     *
     * @return void
     */
    public function searchProducts()
    {
        $this->isAuthorizedForAdminPage();
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
    }
}
<?php 

namespace App\Controllers;

use App\Controllers\Traits\Validatortrait;
use App\Models\Products;

class AdminAnnouncesController extends Controller{
    use Validatortrait;

    public function modifyAnnounces(){
        $this->isAuthorizedForAdminPage();
        if($_SERVER['REQUEST_METHOD']=== 'GET'){
            $pathFile = ROOT . '/carousel.json';
            $carouselTitle = '';
            if(file_exists($pathFile)){
                $fileContent = file_get_contents($pathFile);
                $carouselData = json_decode($fileContent);
                $carouselTitle = $carouselData->carousel_title;
            }
            $productModel = new Products;
            $products = $productModel->getAllProducts();
            $productsAnnounced = [];
            foreach($products as $product){
                if($product->announced){
                    $productsAnnounced[] = $product->id_product;
                }
            }
            $this->render('admin/modifyCarousel', "Modifier la page d'accueil.",
            ['carouselTitle' =>$carouselTitle, 'products' => $products, 'productsAnnounced' => json_encode($productsAnnounced)]);
        }elseif($_SERVER['REQUEST_METHOD']){
            $fields = $this->cleanFields($_POST);
            $title = $fields['title'] ?? '';
            $productsAnnounced = json_decode($fields['products-announced'], true);
            if(empty($title)){
                $errorMessage['blank'] = "Le titre doit être complété.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            $productModel = new Products;
            foreach($fields as $id => $announced){
                if(in_array($id, $productsAnnounced)){
                    $index = array_search($id, $productsAnnounced);
                    unset($productsAnnounced[$index]);
                }
                if($announced == "on" && !in_array($id, $productsAnnounced)){
                    $productModel->updateToAnnounced($id, 1);
                }
            }
            foreach($productsAnnounced as $id){
                $productModel->updateToAnnounced($id, 0);
            }
            $this->sendJSONResponse(['success' => true, 'message' => "La modification a bien été effectuée."]);
            
        }else{
            header("location: /admin/announces/modifyCarousel");
            exit();
        }
    }
}
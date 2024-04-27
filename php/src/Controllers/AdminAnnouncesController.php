<?php 

namespace App\Controllers;

use App\Controllers\Traits\Validatortrait;
use App\Models\Products;

class AdminAnnouncesController extends Controller{
    use Validatortrait;

    /**
     * display the formular to modify the landing page on get method an process the submission on post
     *
     * @return void
     */
    public function modifyAnnounces(){
        $this->isAuthorizedForAdminPage();
        if($_SERVER['REQUEST_METHOD']=== 'GET'){
            $this->displayModifyLandingPageForm();
        }elseif($_SERVER['REQUEST_METHOD']){
            $this->processFormSubmission();            
        }else{
            header("location: /admin/announces/modifyCarousel");
            exit();
        }
    }

    /**
     * display the modify landing page formular
     *
     * @return void
     */
    private function displayModifyLandingPageForm(){
        $pathFile = ROOT . '/carousel.json';
            $carouselTitle = '';
            if(file_exists($pathFile)){
                $fileContent = file_get_contents($pathFile);
                $carouselData = json_decode($fileContent);
                $carouselTitle = $carouselData->carousel_title;
            }
            $productModel = new Products;
            $products = $productModel->getAllProducts();
            //get all products which are selected to announced the landing page
            $productsAnnounced = [];
            foreach($products as $product){
                if($product->announced){
                    $productsAnnounced[] = $product->id_product;
                }
            }
            $this->render('admin/modifyCarousel', "Modifier la page d'accueil.",
            ['carouselTitle' =>$carouselTitle, 'products' => $products, 'productsAnnounced' => json_encode($productsAnnounced)]);
    }

    /**
     * process the submission of the modify landing page formular
     *
     * @return void
     */
    private function processFormSubmission(){
        $fields = $this->cleanFields($_POST);
            $title = $fields['title'] ?? '';
            $productsAnnounced = json_decode($fields['products-announced'], true);
            if(empty($title)){
                $errorMessage['blank'] = "Le titre doit être complété.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            $productModel = new Products;
            foreach($fields as $id => $announced){
                //delete from Announced product which are still annouced.
                if(in_array($id, $productsAnnounced)){
                    $index = array_search($id, $productsAnnounced);
                    unset($productsAnnounced[$index]);
                }
                //if a product was not announced and it become, update it on database
                if($announced == "on" && !in_array($id, $productsAnnounced)){
                    $productModel->updateToAnnounced($id, 1);
                }
            }
            //update to database product which wasn't announced to.
            foreach($productsAnnounced as $id){
                $productModel->updateToAnnounced($id, 0);
            }
            $this->sendJSONResponse(['success' => true, 'message' => "La modification a bien été effectuée."]);
    }
}
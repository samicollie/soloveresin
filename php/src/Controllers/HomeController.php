<?php
namespace App\Controllers;

use App\Models\LegalNotices;
use App\Models\Pictures;
use App\Models\Products;
use App\Services\EmailService;
use App\Controllers\Traits\Validatortrait;
use DateTime;

class HomeController extends Controller {
    use Validatortrait;
    /**
     * display the landing page
     *
     * @return void
     */
    public function index (){
        $productModel = new Products;
        $announcedProducts = $productModel->getAnnouncedProducts();
        $pathFile = ROOT . '/carousel.json';
        $carouselTitle = '';
        if(file_exists($pathFile)){
            $fileContent = file_get_contents($pathFile);
            $carouselData = json_decode($fileContent);
            $carouselTitle = $carouselData->carousel_title;
        }

        $this->render('home/index', 'Accueil : So Love Resin', ['announcedProducts' => $announcedProducts, 'carouselTitle' => $carouselTitle]);

    }

    public function termsOfSale(){
        $legalNoticesModel = new LegalNotices;
        $termsOfSale = $legalNoticesModel->getTermsOfSale();
        $date = new DateTime($termsOfSale->updated_at);
        $updatedDate = $date->format('d-m-Y');
        $this->render('home/termsOfSale', 'Conditions générales de vente', ['termsOfSale' => $termsOfSale, 'updatedDate' => $updatedDate]);
    }

    public function privacyPolicy(){
        $legalNoticesModel = new LegalNotices;
        $privacyPolicy = $legalNoticesModel->getPrivacyPolicy();
        $date = new DateTime($privacyPolicy->updated_at);
        $updatedDate = $date->format('d-m-Y');
        $this->render('home/privacyPolicy', 'Politique de confidentialité', ['privacyPolicy' => $privacyPolicy, 'updatedDate' => $updatedDate]);
    }

    public function usageCookie(){
        $legalNoticesModel = new LegalNotices;
        $usageCookie = $legalNoticesModel->getUsageCookie();
        $date = new DateTime($usageCookie->updated_at);
        $updatedDate = $date->format('d-m-Y');
        $this->render('home/usageCookie', 'Utilisation des cookies', ['usageCookie' => $usageCookie, 'updatedDate' => $updatedDate]);
    }

    public function aboutUs(){
        $this->render('home/aboutUs', 'A propos de Nous');
    }


    public function contactUs(){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->render('home/contactUs', 'Nous contacter');
        }elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
            $fields = $this->cleanFields($_POST);
            $errorMessage = $this->validateFields($fields);
            $email = $fields['email'] ?? '';
            $message = $fields['message'] ?? '';
            $title = $fields['title'] ?? '';
            if(empty($email) || empty($message) || empty($title)){
                $errorMessage['blank'] = "Tous les champs doivent être complétés";
            }
            if(!empty($errorMessage)){
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            ob_start();
            $result = EmailService::sendEmail($email,'soloveresin@gmail.com',$title, nl2br($message), true);
            ob_get_clean();
            if(!$result){
                $errorMessage['request'] = "Une erreur s'est produite lors de l'envoi de l'email.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            $this->sendJSONResponse(['success' => true, 'message' => "L'email a bien été envoyé."]);
        }else{
            header("location: /");
            exit();
        }
    }
}
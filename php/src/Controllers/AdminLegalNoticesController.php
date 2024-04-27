<?php 

namespace App\Controllers;

use App\Controllers\Traits\Validatortrait;
use App\Models\LegalNotices;

class AdminLegalNoticesController extends Controller{
    use Validatortrait;

    /**
     * display the list of the legal notices
     *
     * @return void
     */
    public function legalNoticesList(){
        $this->isAuthorizedForAdminPage();
        $this->render('admin/legalNoticesList', 'Mentions légales');
    }

    /**
     * display the terms of sale form on get method and process submission on post
     *
     * @return void
     */
    public function termsOfSale(){
        $this->isAuthorizedForAdminPage();
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->displayTermsOfSaleForm();
        }elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->processTermsOfSaleFormSubmission();
        }else{
            header("location: /admin/legalNotices/termsofsale");
            exit();
        }
    }

    /**
     * display the terms of sale formular
     *
     * @return void
     */
    private function displayTermsOfSaleForm(){
        $legalNoticesModel= new LegalNotices;
        $termsOfSale = $legalNoticesModel->getTermsOfSale();
        $this->render('admin/termsOfSale', 'Conditions générales de vente', ['termsOfSale' => $termsOfSale]);
    }

    /**
     * process the submission of terms of sale formular
     *
     * @return void
     */
    private function processTermsOfSaleFormSubmission(){
        $fields = $this->cleanFields($_POST);
        $termsId = intval($fields['terms-id']) ?? '';
        if(empty($termsId)){
            header("location: /admin/legalNotices/termsofsale");
            exit();
        }
        $sailorId = $fields['sailor-id'] ?? '';
        $productsAndPrices = $fields['products-prices'] ?? '';
        $commandProcess = $fields['command-process'] ?? '';
        $payement = $fields['payement'] ?? '';
        $delivery = $fields['delivery'] ?? '';
        $rightOfCancellation = $fields['right-of-cancellation'] ?? '';
        $disputes = $fields['disputes'] ?? '';
        $modification = $fields['modification'] ?? '';
        if(empty($sailorId) || empty($productsAndPrices) || empty($commandProcess) || empty($payement) || empty($delivery)
        || empty($rightOfCancellation) || empty($disputes) || empty($modification)){
            $errorMessage['blank'] = "Tous les champs doivent être complétés.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $legalNoticesModel = new LegalNotices;
        if(!$legalNoticesModel->updateTermsOfSale($termsId, $sailorId, $productsAndPrices, $commandProcess, $payement, 
        $delivery, $rightOfCancellation, $disputes, $modification)){
            $errorMessage['request'] = "Une erreur s'est produite lors de la mise à jour des conditions générales de vente.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $this->sendJSONResponse(['success' => true, 'message' => 'Les conditions ont été mises à jour avec succés.']);
    }

    /**
     * display privacy policy formular on get method and process the submission on post
     *
     * @return void
     */
    public function privacyPolicy(){
        $this->isAuthorizedForAdminPage();
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->displayPrivacyPolicyForm();
        }elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->processPrivacyPolicyFormSubmission();
        }else{
            header("location: /admin/legalNotices/privacypolicy");
            exit();
        }
    }

    /**
     * display the privacy policy formular
     *
     * @return void
     */
    private function displayPrivacyPolicyForm(){
        $legalNoticesModel = new LegalNotices;
        $privacyPolicy = $legalNoticesModel->getPrivacyPolicy();
        $this->render('admin/privacyPolicy', 'Politique de confidentialité', ['privacyPolicy' => $privacyPolicy]);
    }

    /**
     * process the submission of the privacy policy formular
     *
     * @return void
     */
    private function processPrivacyPolicyFormSubmission(){
        $fields = $this->cleanFields($_POST);
        $policyId = $fields['policy-id'] ?? '';
        $collection = $fields['collection'] ?? '';
        $usage = $fields['usage'] ?? '';
        $conservation = $fields['conservation'] ?? '';
        $security = $fields['security'] ?? '';
        $cookie = $fields['cookie'] ?? '';
        $usersRights = $fields['users-rights'] ?? '';
        $disputes = $fields['disputes'] ?? '';
        $contact = $fields['contact'] ?? '';
        if(empty($policyId)){
            header("location: /admin/legalNotices/privacypolicy");
            exit();
        }
        if(empty($collection) || empty($usage) || empty($conservation) || empty($security) || empty($cookie)
        || empty($usersRights) || empty($disputes) || empty($contact)){
            $errorMessage['blank'] = "Tous les champs doivent être complétés.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $legalNoticesModel = new LegalNotices;
        if(!$legalNoticesModel->updatePrivacyPolicy($policyId, $collection, $usage, $conservation, $cookie, $security, $usersRights, $disputes, $contact)){
            $errorMessage['request'] = "Une erreur s'est produite lors de la mise à jour de la politique de confidentialité.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $this->sendJSONResponse(['success' => true, 'message' => 'La politique de confidentialité a été mise à jour.']);
    }

    /**
     * display the usage cookie form on get method and process submission on post
     *
     * @return void
     */
    public function usageCookie(){
        $this->isAuthorizedForAdminPage();
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->displaUsageCookieForm();
        }elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->processUsageCookieFormSubmission();
        }else{
            header("location: /admin/legalNotices/usagecookie");
            exit();
        }
    }

    /**
     * display the usage cookie form
     *
     * @return void
     */
    private function displaUsageCookieForm(){
        $legalNoticesModel = new LegalNotices;
        $usageCookie = $legalNoticesModel->getUsageCookie();
        $this->render('admin/usageCookie', 'Utilisation de Cookie', ['usageCookie' => $usageCookie]);
    }

    /**
     * process the submission of the usage cookie form
     *
     * @return void
     */
    private function processUsageCookieFormSubmission(){
        $fields = $this->cleanFields($_POST);
        $usageId = $fields['usage-id'] ?? '';
        $usageContent = $fields['content'] ?? '';
        if(empty($usageId)){
            header("location: /admin/legalNotices/usagecookie");
            exit();
        }
        if(empty($usageContent)){
            $errorMessage['blank'] = "Tous les champs doivent être complétés.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $legalNoticesModel = new LegalNotices;
        if(!$legalNoticesModel->updateUsageCookie($usageId, $usageContent)){
            $errorMessage['request'] = "Une erreur s'est produite lors de la mise à jour de l'utilisation des cookies.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $this->sendJSONResponse(['success' => true, 'message' => "L'utilisation des cookies a bien été mise à jour."]);
    }
}
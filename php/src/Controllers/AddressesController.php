<?php 

namespace App\Controllers;

use App\Models\Users;
use App\Models\Session;
use App\Models\Addresses;
use App\Controllers\Traits\Validatortrait;




class AddressesController extends Controller
{
    use Validatortrait;
    
    /**
     * display a formular to add an address
     *
     * @return void
     */
    public function indexAddAddress(array $param=null){
        $this->isAccessAllowed();
        $order = false;
        if(!empty($param)){
            $order = true;
        }
        $this->render("user/indexAddAddressFormular", "Ajouter une adresse", ['order' => $order]);
    }

    /**
    * add an adress for an user in database
    *
    * @return void
    */
    public function addAddress(){
        $this->isAccessAllowed();
        $idUser = $_SESSION['id_user'];
        $fields = $this->cleanFields($_POST);
        $errorMessage = $this->validateFields($fields);
        $firstname = $fields['firstname'] ?? '';
        $lastname = $fields['lastname'] ?? '';
        $streetNumber = $fields['street-number'] ?? '';
        $streetName = $fields['street-name'] ?? '';
        $zipcode = $fields['zipcode'] ?? '';
        $city = $fields['city'] ?? '';
        if(empty($firstname) || empty($lastname) || empty($streetName) ||
        empty($zipcode) || empty($city)) {
            $errorMessage['blank'] = "Tous les champs doivent être complétés";
        }
        if(!empty($errorMessage)){
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $addressModel = new Addresses;
        if(!$addressModel->addAddress($firstname, $lastname, $streetNumber, $streetName, $zipcode, $city, $idUser)){
            $errorMessage['request']= "Une erreur s'est produite lors de l'ajout";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $this->sendJSONResponse(['success' => true]);
    }

    /**
     * display the formular to modify an address
     *
     * @param array $param
     * @return void
     */
    public function modifyAddress(array $param){
        $this->isAccessAllowed();
        $id = intval($param[0]);
        $addressModel = new Addresses;
        $address = $addressModel->getAddress($id);
        $this->render("user/modifyAddress", "Modifier l'adresse", ['address' => $address]);
    }

    /**
     * update an address from an user
     *
     * @return void
     */
    public function updateAddress(){
        $this->isAccessAllowed();
        $fields = $this->cleanFields($_POST);
        $errorMessage = $this->validateFields($_POST);
        $firstname = $fields['firstname'] ?? '';
        $lastname = $fields['lastname'] ?? '';
        $streetNumber = $fields['street-number'] ?? '';
        $streetName = $fields['street-name'] ?? '';
        $zipcode = $fields['zipcode'] ?? '';
        $city = $fields['city'] ?? '';
        if(empty($firstname) || empty($lastname) ||
        empty($streetName) ||
        empty($zipcode) || empty($city)) {
            $errorMessage['blank'] = "Tous les champs doivent être complétés";
        }
        if(!empty($errorMessage)){
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }

        $addressModel = new Addresses;
        if(!isset($_POST['id_address'])){
            header("location: /profile");
            exit();
        }
        $idAddress = htmlspecialchars(strip_tags($_POST['id_address']));
        if(!$addressModel->updateAddress($firstname, $lastname, $streetNumber, $streetName, $zipcode, $city, $idAddress)){
            $errorMessage['request'] ="Une erreur s'est produite.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $this->sendJSONResponse(['success' => true]);
    }

    /**
     * delete an user's address
     *
     * @return void
     */
    public function deleteAddress(){
        $this->isAccessAllowed();
        $sessionModel = new Session;
        $idUser = $_SESSION['id_user'];
        if(!isset($_POST['id_address'])){
            header("location: /profile");
            exit();
        }
        $idAddress = htmlspecialchars(strip_tags($_POST['id_address']));
        $addressModel = new Addresses;
        $result = $addressModel->deleteAddress($idAddress);
        if($result){
            header("location: /profile");
            exit();
        }
        $errorMessage="Une erreur s'est produite lors de la suppression";
        $userModel = new Users;
        $user = $userModel->getuserById($idUser);
        $addressesModel = new Addresses;
        $addresses = $addressesModel->getAddresses($idUser);
        $this->render("user/indexProfile", "Mon Profil", ['user' => $user, 'addresses' => $addresses, 'errorMessage' => $errorMessage]);
    }
}
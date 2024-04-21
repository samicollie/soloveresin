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
    public function indexAddAddress(){
        $this->isAccessAllowed();
        $this->render("user/indexAddAddressFormular", "Ajouter une adresse");
    }

    /**
    * add an adress for an user in database
    *
    * @return void
    */
    public function addAddress(){
        $this->isAccessAllowed();
        $sessionModel = new Session;
        $idUser = $sessionModel->getSession($_COOKIE['session'])->id_user;
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
        $result = $addressModel->addAddress($firstname, $lastname, $streetNumber, $streetName, $zipcode, $city, $idUser);
        if($result){
            $this->sendJSONResponse(['success' => true]);
        }else{
            $errorMessage['request']= "Une erreur s'est produite lors de l'ajout";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
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
        if(isset($_POST['id_address'])){
            $idAddress = htmlspecialchars(strip_tags($_POST['id_address']));
            $result = $addressModel->updateAddress($firstname, $lastname, $streetNumber, $streetName, $zipcode, $city, $idAddress);
            if($result){
                $this->sendJSONResponse(['success' => true]);
            }else{
                $errorMessage['request'] ="Une erreur s'est produite.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
        }else{
            header("location: /profile");
        }
    }

    /**
     * delete an user's address
     *
     * @return void
     */
    public function deleteAddress(){
        $this->isAccessAllowed();
        $sessionModel = new Session;
        $idUser = $sessionModel->getSession($_COOKIE['session'])->id_user;
        if(isset($_POST['id_address'])){
            $idAddress = htmlspecialchars(strip_tags($_POST['id_address']));
            $addressModel = new Addresses;
            $result = $addressModel->deleteAddress($idAddress);
            if($result){
                header("location: /profile");
            }else{
                $errorMessage="Une erreur s'est produite lors de la suppression";
                $userModel = new Users;
                $user = $userModel->getuserById($idUser);
                $addressesModel = new Addresses;
                $addresses = $addressesModel->getAddresses($idUser);
                $this->render("user/indexProfile", "Mon Profil", ['user' => $user, 'addresses' => $addresses, 'errorMessage' => $errorMessage]);
            }
        }else{
            header("location: /profile");
        }
    }
}
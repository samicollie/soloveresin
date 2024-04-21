<?php 

namespace App\Controllers;

use App\Models\Users;
use App\Models\Session;
use App\Models\Addresses;
use App\Controllers\Traits\Validatortrait;




class AccountController extends Controller
{
    use Validatortrait;
    /**
     * display the profile page
     *
     * @return void
     */
    public function indexProfile(){
        $this->isAccessAllowed();
        $idUser = $_SESSION['id_user'];
        $userModel = new Users;
        $user = $userModel->getuserById($idUser);
        $addressesModel = new Addresses;
        $addresses = $addressesModel->getAddresses($idUser);
        $this->render("user/indexProfile", "Mon Profil", ['user' => $user, 'addresses' => $addresses]);
    }

    /**
     * display the formular to modify identity and contact info from an user
     *
     * @param array $param
     * @return void
     */
    public function modifyContact(array $param){
        $this->isAccessAllowed();
        $idUser = intval($param[0]);
        $userModel = new Users;
        $user = $userModel->getUserById($idUser);
        $this->render("user/modifyContact", "Modifier mon profil", ['user' => $user]);
    }

    /**
     * update information of an user
     *
     * @return void
     */
    public function updateContact(){
        $this->isAccessAllowed();
        $sessionModel = new Session;
        $idUser = $sessionModel->getSession($_COOKIE['session'])->id_user;
        $fields = $this->cleanFields($_POST);
        $errorMessage = $this->validateFields($_POST);
        $phoneNumber = $fields['phone-number'] ?? '';
        $firstname = $fields['firstname'] ?? '';
        $lastname = $fields['lastname'] ?? '';
        if(empty($firstname) || empty($lastname) && empty($email)){
            $errorMessage['blank'] = "Tous les champs doivent être complétés";
        }
        if(!empty($errorMessage)){
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $userModel = new Users;
        $userModel->updateUser($firstname, $lastname, $phoneNumber, $idUser );
        $this->sendJSONResponse(['success' => true]);
        exit();
    }
}
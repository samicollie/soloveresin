<?php 

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Users;
use App\Models\Session;
use App\Models\Addresses;

class UserController extends Controller{

    /**
     * display the formular to register an user
     *
     * @return void
     */
    public function indexRegister(){

        if($this->isLoggedIn){
            header("location: /profile");
        }
        $this->render("user/indexRegister", "Inscription So Love Resin");
    }

    /**
     * register an user in database
     *
     * @return void
     */
    public function registerUser(){
        if(!$this->isLoggedIn){
            if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmation-password'])){
                if(!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirmation-password'])){
                    $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                    $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                    $email = htmlspecialchars(strip_tags($_POST['email']));
                    $password = htmlspecialchars(strip_tags($_POST['password']));
                    $confirmationPassword = htmlspecialchars(strip_tags($_POST['confirmation-password']));
                    $userModel = new Users;
                    $isExisting = $userModel->verifyUserAccount($email);
                    if($isExisting){
                        $errorMessage = "Cet email est déjà utilisé";
                        $this->render("user/indexRegister", "Inscription So Love Resin", ['errorMessage' => $errorMessage]);
                    }else{
                        if($password === $confirmationPassword){
                            if($userModel->newUser($firstname, $lastname, $email, $password)){
                                $successMessage = "Votre compte a été enregistré";
                                $this->render("user/indexLogin", "Connexion So Love Resin", ["successMessage" => $successMessage]);
                            }
                        }else{
                            $errorMessage = "Les deux mots de passes doivent être identitques";
                            $this->render("user/indexRegister", "Inscription So Love Resin", ['errorMessage' => $errorMessage]);
                        }
                    }
                }else{
                    $errorMessage = "Tous les champs doivent être complétés";
                    $this->render("user/indexRegister", "Inscription So Love Resin", ['errorMessage' => $errorMessage]);
                }
            }
        }else{
            header("location: /profile");
        }
        
    }

    /**
     * display the formular to login an user
     *
     * @return void
     */
    public function indexLogin(){
        if($this->isLoggedIn){
            header("location: /profile");
        }
        $this->render("user/indexLogin", "Connexion So Love Resin");
    }

    /**
     * login an user
     *
     * @return void
     */
    public function loginUser(){
        if($this->isLoggedIn){
            if(isset($_POST['email']) && isset($_POST['password'])){
                if(!empty($_POST['email']) && !empty($_POST['password'])){
                    $email = htmlspecialchars(strip_tags($_POST['email']));
                    $password = htmlspecialchars(strip_tags($_POST['password']));
                    $userModel = new Users;
                    $isExisting = $userModel->verifyUserAccount($email);
                    if($isExisting){
                        $user = $userModel->getUser($email)[0];
                        if(password_verify($password, $user->password)){
                            $idUser = $user->id_user;
                            $sessionModel = new Session;
                            $cartModel = new Cart;
                            $idCart = $cartModel->isExistingCart($idUser);
                            $sessionId = bin2hex(random_bytes(32));
                            $sessionModel->addNewSession($sessionId, $idUser);
                            $signature = hash_hmac('sha256', $sessionId, 'Mot de Passe de Signature');
                            setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, "", "", false, true);
                            setcookie('signature', $signature, time() + 60 * 60 * 24 * 14, "", "", false, true);
                            if(isset($_COOKIE['cart'])){
                                $cart = json_decode($_COOKIE['cart'], true);
                                if($idCart){
                                    foreach($cart as $item){
                                        $productId = $item['id'];
                                        $quantity = $item['quantity'];
                                        if($cartModel->isExistingProduct($idCart, $productId)){
                                            $cartModel->updateQuantity($idCart, $productId, $quantity);
                                        }else{
                                            $cartModel->fromCookieToCart($idCart, $productId, $quantity);
                                        }
                                    }
                                }else{
                                    $cartModel->createUserCart($idUser);
                                    $idCart = $cartModel->isExistingCart($idUser);
                                    foreach($cart as $item){
                                        $productId = $item['id'];
                                        $quantity = $item['quantity'];
                                        $cartModel->fromCookieToCart($idCart, $productId, $quantity);
                                    }
                                }
                                setcookie('cart', '', time());
                            }
                            $cartSignature = hash_hmac('sha256', $idCart, 'Mot de Passe de Signature');
                            setcookie('cartId', $idCart, time() + 60 * 60 * 24 * 14, "", "", false, true);
                            setcookie('cartSignature', $cartSignature, time() + 60 * 60 * 24 * 14, "", "", false, true);
                            header("location: /profile");
                        }else{
                            $errorMessage ="Email ou mot de passe incorrect";
                            $this->render("user/indexLogin", "Connexion So Love Resin", ['errorMessage' => $errorMessage]);
                        }
                    }else{
                        $errorMessage = "Email ou mot de passe incorrect";
                        $this->render("user/indexLogin", "Connexion So Love Resin", ['errorMessage' => $errorMessage]);
                    }
                }else{
                    $errorMessage = "Tous les champs doivent être complétés";
                    $this->render("user/indexLogin", "Connexion So Love Resin", ['errorMessage' => $errorMessage]);
                }
            }
        }else{
            header("location: /profile");
        }
    }

    /**
     * logout an user
     *
     * @return void
     */
    public function logoutUser(){
        if($this->isLoggedIn){
            $sessionModel = new Session;
            $session = $sessionModel->getSession($_COOKIE['session'])[0];
            $sessionModel->deleteSession($session->id_user);
            setcookie('session', '', time());
            setcookie('signature', '', time());
            setcookie('cartId', '', time());
            setcookie('cartSignature', '', time());
            header("location: /login");
        }else{
            header("location: /login");
        }
    }

    /**
     * display the profile page
     *
     * @return void
     */
    public function indexProfile(){
            if($this->isLoggedIn){
                $sessionModel = new Session;
                $idUser = $sessionModel->getSession($_COOKIE['session'])[0]->id_user;
                $userModel = new Users;
                $user = $userModel->getuserById($idUser)[0];
                $addressesModel = new Addresses;
                $addresses = $addressesModel->getAddresses($idUser);
                $this->render("user/indexProfile", "Mon Profil", ['user' => $user, 'addresses' => $addresses]);
            }else{
                header("location: /login");
            }
            
    }

    /**
     * display the formular to modify identity and contact info from an user
     *
     * @param array $param
     * @return void
     */
    public function modifyContact(array $param){
            if($this->isLoggedIn){
                $idUser = intval($param[0]);
                $userModel = new Users;
                $user = $userModel->getUserById($idUser)[0];
                $this->render("user/modifyContact", "Modifier mon profil", ['user' => $user]);
            }else{
                header("location: /login");
            }
            
    }

    /**
     * update information of an user
     *
     * @return void
     */
    public function updateContact(){
            if($this->isLoggedIn){
                $sessionModel = new Session;
                $idUser = $sessionModel->getSession($_COOKIE['session'])[0]->id_user;
                if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email'])){
                    if(!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email'])){
                        $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                        $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                        $email = htmlspecialchars(strip_tags($_POST['email']));
                        if(isset($_POST['phone_number'])){
                            $phoneNumber = htmlspecialchars(strip_tags($_POST['phone_number']));
                        }else{
                            $phoneNumber = '';
                        }
                        $userModel = new Users;
                        $userModel->updateUser($firstname, $lastname, $email, $phoneNumber, $idUser );
                        header("location: /profile");
                    }else{
                        $errorMessage = "Tous les champs doivent être complétés";
                        $userModel= new Users;
                        $user = $userModel->getUserById($idUser)[0];
                        $this->render("user/modifyContact", "Modifier mon profil", ['user' => $user,'errorMessage' => $errorMessage]);
                    }
                }else{
                    header("location: /login");
                }
            }else{
                header("location: /login");
            }
    }

    /**
     * display the formular to modify an address
     *
     * @param array $param
     * @return void
     */
    public function modifyAddress(array $param){
            if($this->isLoggedIn){
                $id = intval($param[0]);
                $addressModel = new Addresses;
                $address = $addressModel->getAddress($id)[0];
                $this->render("user/modifyAddress", "Modifier l'adresse", ['address' => $address]);
            }else{
                header("location: /login");
            }
    }

    /**
     * update an address from an user
     *
     * @return void
     */
    public function updateAddress(){
            if($this->isLoggedIn){
                if(isset($_POST['firstname']) && isset($_POST['lastname']) && 
                isset($_POST['street_number']) && isset($_POST['street_name']) &&
                isset($_POST['zipcode']) && isset($_POST['city'])){
                    if(!empty($_POST['firstname']) && !empty($_POST['lastname']) &&
                    !empty($_POST['street_number']) && !empty($_POST['street_name']) &&
                    !empty($_POST['zipcode']) && !empty($_POST['city'])) {
                        $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                        $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                        $streetNumber = htmlspecialchars(strip_tags($_POST['street_number']));
                        $streetName = htmlspecialchars(strip_tags($_POST['street_name']));
                        $zipcode = htmlspecialchars(strip_tags($_POST['zipcode']));
                        $city = htmlspecialchars(strip_tags($_POST['city']));
                        $addressModel = new Addresses;
                        if(isset($_POST['id_address'])){
                            $idAddress = htmlspecialchars(strip_tags($_POST['id_address']));
                            $result = $addressModel->updateAddress($firstname, $lastname, $streetNumber, $streetName, $zipcode, $city, $idAddress);
                            if($result){
                                header("location: /profile");
                            }else{
                                $errorMessage= "Une erreur s'est produite";
                                $addressModel = new Addresses;
                                $idAddress = htmlspecialchars(strip_tags($_POST['id']));
                                $address = $addressModel->getAddress($idAddress)[0];
                                $this->render("user/modifyAddress", "Modifier l'adresse", ['errorMessage' => $errorMessage, 'address' => $address]);
                            }
                        }
                    }else{
                        $errorMessage = "Tous les champs doivent être complétés";
                        $addressModel = new Addresses;
                        $idAddress = htmlspecialchars(strip_tags($_POST['id']));
                        $address = $addressModel->getAddress($idAddress)[0];
                        $this->render("user/modifyAddress", "Modifier l'adresse", ['errorMessage' => $errorMessage, 'address' => $address]);
                    }
                }else{
                    header("location: /login");
                }
            }else{
                header("location: /login");
            }
    }

    /**
     * delete an user's address
     *
     * @return void
     */
    public function deleteAddress(){
            if($this->isLoggedIn){
                $sessionModel = new Session;
                $idUser = $sessionModel->getSession($_COOKIE['session'])[0]->id_user;
                if(isset($_POST['id_address'])){
                    $idAddress = htmlspecialchars(strip_tags($_POST['id_address']));
                    $addressModel = new Addresses;
                    $result = $addressModel->deleteAddress($idAddress);
                    if($result){
                        header("location: /profile");
                    }else{
                        $errorMessage="Une erreur s'est produite lors de la suppression";
                        $userModel = new Users;
                        $user = $userModel->getuserById($idUser)[0];
                        $addressesModel = new Addresses;
                        $addresses = $addressesModel->getAddresses($idUser);
                        $this->render("user/indexProfile", "Mon Profil", ['user' => $user, 'addresses' => $addresses, 'errorMessage' => $errorMessage]);
                    }
                }
            }else{
                header("location: /login");
            }
    }

    /**
     * display a formular to add an address
     *
     * @return void
     */
    public function indexAddAddress(){
            if($this->isLoggedIn){
                $this->render("user/indexAddAddressFormular", "Ajouter une adresse");
            }else{
                header("location: /login");
            }
            
    }

    /**
     * add an adress for an user in database
     *
     * @return void
     */
    public function addAddress(){
            if($this->isLoggedIn){
                $sessionModel = new Session;
                $idUser = $sessionModel->getSession($_COOKIE['session'])[0]->id_user;
                if(isset($_POST['firstname']) && isset($_POST['lastname']) && 
                isset($_POST['street_number']) && isset($_POST['street_name']) &&
                isset($_POST['zipcode']) && isset($_POST['city'])){
                    if(!empty($_POST['firstname']) && !empty($_POST['lastname']) &&
                    !empty($_POST['street_number']) && !empty($_POST['street_name']) &&
                    !empty($_POST['zipcode']) && !empty($_POST['city'])) {
                        $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                        $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                        $streetNumber = htmlspecialchars(strip_tags($_POST['street_number']));
                        $streetName = htmlspecialchars(strip_tags($_POST['street_name']));
                        $zipcode = htmlspecialchars(strip_tags($_POST['zipcode']));
                        $city = htmlspecialchars(strip_tags($_POST['city']));
                        $addressModel = new Addresses;
                        $result = $addressModel->addAddress($firstname, $lastname, $streetNumber, $streetName, $zipcode, $city, $idUser);
                        if($result){
                            header("location: /profile");
                        }else{
                            $errorMessage= "Une erreur s'est produite lors de l'ajout";
                            $this->render("user/indexAddAddressFormular", "Ajouter une adresse", ['errorMessage' => $errorMessage]);
                        }
                    }else{
                        $errorMessage = "Tous les champs doivent être complétés";
                        $addressModel = new Addresses;
                        $this->render("user/indexAddAddressFormular", "Ajouter une adresse", ['errorMessage' => $errorMessage]);
                    }
                }
            }else{
                header("location: /login");
            }
    }
}
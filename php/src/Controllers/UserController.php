<?php 

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Users;
use App\Models\Session;
use App\Models\Addresses;
use App\Controllers\Traits\Validatortrait;
use App\Services\EmailService;
use DateTime;

class UserController extends Controller{
    use Validatortrait;

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
                    $errorMessage = [];
                    if(!$this->validateFirstname($_POST['firstname'])){
                        $errorMessage['firstname'] = "Le prénom doit contenir entre 3 et 25 caractères sans chiffre ni caractères spéciaux.";
                    }
                    if(!$this->validateLastname($_POST['lastname'])){
                        $errorMessage['lastname'] = "Le Nom doit contenir entre 2 et 20 caractères sans chiffre ni caractères spéciaux.";
                    }
                    if(!$this->validateEmail($_POST['email'])){
                        $errorMessage['email'] = "L'adresse email n'est pas valide.";
                    }
                    if(!$this->validatePassword($_POST['password'])){
                        $errorMessage['password'] = "Le mot de passe doit contenir au moins 8 caractère avec une minuscule, une majuscule, un chiffre et un caractère spécial.";
                    }
                    if(!$this->validateConfirmPassword($_POST['password'], $_POST['confirmation-password'])){
                        $errorMessage['confirmationPassword'] = "Les deux mots de passe doivent être identiques.";
                    }
                    if(!empty($errorMessage)){
                        $response = ['success' => false , 'errorMessage' => $errorMessage];
                        echo json_encode($response);
                        exit();
                    }else{
                        $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                        $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                        $email = htmlspecialchars(strip_tags($_POST['email']));
                        $password = htmlspecialchars(strip_tags($_POST['password']));
                        $userModel = new Users;
                        $isExisting = $userModel->verifyUserAccount($email);
                        if($isExisting){
                            $errorMessage['email'] = "Cet email est déjà utilisé";
                            $response = ['success' => false, 'errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit();
                        }else{
                            $resetToken = bin2hex(random_bytes(32));
                            date_default_timezone_set('Europe/Paris');
                            $date = new DateTime();
                            $date->modify('+1 hour');
                            $createdAt = $date->format('Y-m-d H:i:s');
                            if($userModel->newUser($firstname, $lastname, $email, $password, $resetToken, $createdAt)){
                                $subject = "Validation compte";
                                $body = "Félicitations ! <br>
                                Votre compte a été crée, pour le valider, veuillez cliquer sur le lien suivant : <br><br>
                                <a href='localhost/validation/account/" . $resetToken . "'>Valider mon compte</a>";
                                $from = 'testsoloveresin@gmail.com';
                                $to = $email;
                                ob_start();
                                EmailService::sendEmail($from, $to, $subject, $body, true, 'So Love Resin');
                                ob_get_clean();
                                $response = ['success' => true];
                                echo json_encode($response);
                                exit();
                            }
                        }
                    }
                }else{
                    $errorMessage['blank'] = "Tous les champs doivent être complétés";
                    $response = ['success' => false, 'errorMessage' => $errorMessage];
                    echo json_encode($response);
                    exit();
                }
            }else{
                header("location: /register");
            }
        }else{
            header("location: /profile");
        }
        
    }

    /**
     * display the success register page
     *
     * @return void
     */
    public function successRegister(){

        $this->render('user/successRegister', 'Enregistrement réussi');
    }

    /**
     * validate the account of an user
     *
     * @param array $param
     * @return void
     */
    public function validateAccount(array $param){
        $resetToken = $param[0];
        $userModel = new Users;
        $user = $userModel->getUserWithResetToken($resetToken);
        if($user){
            if($user[0]->is_verified){
                header("location: /login");
            }
            $date = new DateTime();
            if($date > new DateTime($user[0]->created_at)){
                $errorMessage = "Désolé le lien est invalide.";
                $this->render('user/verifiedAccount', 'Validation du compte', ['errorMessage' => $errorMessage]);
            }else{
                $userModel->makeVerified($user[0]->id_user);
                $successMessage = "Votre compte est à présent vérifié.";
                $this->render('user/verifiedAccount', 'Validation du compte', ['successMessage' => $successMessage]);
            }
        }else{
            $errorMessage = "Désolé le lien est invalide.";
            $this->render('user/verifiedAccount', 'Validation du compte', ['errorMessage' => $errorMessage]);
        }
    }

    /**
     * display the generate link formular and verify the date on post method
     *
     * @return void
     */
    public function generateLink(){
        if(isset($_POST['email'])){
            if(!empty($_POST['email'])){
                $email = htmlspecialchars(strip_tags($_POST['email']));
                $userModel = new Users;
                $user = $userModel->getUserByEmail($email);
                if($user){
                    if($user[0]->is_verified){
                        $response = ['isVerified' => true];
                        echo json_encode($response);
                        exit();
                    }else{
                        if($user[0]->count_link < 5){
                            $resetToken = bin2hex(random_bytes(32));
                            date_default_timezone_set('Europe/Paris');
                            $date = new DateTime();
                            $date->modify('+1 hour');
                            $createdAt = $date->format('Y-m-d H:i:s');
                            $userModel->updateResetToken($user[0]->id_user, $resetToken, $createdAt);
                            $userModel->updateCountLink($user[0]->id_user);
                            $subject = "Validation compte";
                            $body = "Voici le nouveau lien de validation de votre compte : <br><br>
                            <a href='localhost/validation/account/" . $resetToken . "'>Valider mon compte</a>";
                            $from = 'testsoloveresin@gmail.com';
                            $to = $email;
                            ob_start();
                            EmailService::sendEmail($from, $to, $subject, $body, true, 'So Love Resin');
                            ob_get_clean();
                            $response = ['success' => true];
                            echo json_encode($response);
                            exit();
                        }
                    }
                }else{
                    $errorMessage['email'] = "Cet email n'existe pas";
                    $response = ['success' => false, 'errorMessage' => $errorMessage];
                    echo json_encode($response);
                    exit();
                }
            }else{
                $errorMessage['blank'] = "Le champ ne peut pas être vide";
                $response = ['success' => false, 'errorMessage' => $errorMessage];
                echo json_encode($response);
                exit();
            }
        }else{
            $this->render('user/generateLink', 'Générer un nouveau lien');
        }
    }

    public function confirmationLink(){
        $this->render('user/confirmationLink', 'Confirmation d\'envoi du lien');
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
        if(!$this->isLoggedIn){
            if(isset($_POST['email']) && isset($_POST['password'])){
                if(!empty($_POST['email']) && !empty($_POST['password'])){
                    $email = htmlspecialchars(strip_tags($_POST['email']));
                    $password = htmlspecialchars(strip_tags($_POST['password']));
                    $userModel = new Users;
                    $isExisting = $userModel->verifyUserAccount($email);
                    if($isExisting){
                        if($userModel->isVerified($email)){
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
                                $response = ['success' => true];
                                echo json_encode($response);
                                exit();
                            }else{
                                $errorMessage['email'] = "Email ou mot de passe incorrect";
                                $response = ['errorMessage' => $errorMessage];
                                echo json_encode($response);
                                exit();
                            }
                        }else{
                            $errorMessage['email'] = "Email ou mot de passe incorrect";
                            $response = ['errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit();
                        }
                    }else{
                        $errorMessage['email'] = "Email ou mot de passe incorrect";
                        $response = ['errorMessage' => $errorMessage];
                        echo json_encode($response);
                        exit();
                    }
                }else{
                    $errorMessage['blank'] = "Tous les champs doivent être complétés";
                    $response = ['errorMessage' => $errorMessage];
                    echo json_encode($response);
                    exit();
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
                if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['phone-number'])){
                    if(!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['email'])){
                        $errorMessage = [];
                        if(!$this->validateFirstname($_POST['firstname'])){
                            $errorMessage['firstname'] = "Le prénom doit contenir entre 3 et 25 caractères sans chiffre ni caractères spéciaux.";
                        }
                        if(!$this->validateLastname($_POST['lastname'])){
                            $errorMessage['lastname'] = "Le Nom doit contenir entre 2 et 20 caractères sans chiffre ni caractères spéciaux.";
                        }
                        if(!$this->validateEmail($_POST['email'])){
                            $errorMessage['email'] = "L'adresse email n'est pas valide.";
                        }
                        if(!$this->validateEmail($_POST['email'])){
                            $errorMessage['email'] = "L'adresse email n'est pas valide.";
                        }
                        if(!empty($_POST['phone-number'])){
                            if(!$this->validatePhoneNumber($_POST['phone-number'])){
                                $errorMessage['phoneNumber'] = "Le numéro de téléphone n'est pas valide.";
                            }
                        }
                        if(!empty($errorMessage)){
                            $response = ['errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit();
                        }else{
                            $phoneNumber = htmlspecialchars(strip_tags($_POST['phone-number']));
                            $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                            $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                            $email = htmlspecialchars(strip_tags($_POST['email']));
                            $userModel = new Users;
                            $userModel->updateUser($firstname, $lastname, $email, $phoneNumber, $idUser );
                            $response = ['success' => true];
                            echo json_encode($response);
                        }
                    }else{
                        $errorMessage['blank'] = "Tous les champs doivent être complétés";
                        $response = ['errorMessage' => $errorMessage];
                        echo json_encode($response);
                        exit();
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
                        $errorMessage = [];
                        if(!$this->validateFirstname($_POST['firstname'])){
                            $errorMessage['firstname'] = "Le prénom doit contenir entre 3 et 25 caractères sans chiffre ni de caractères spéciaux.";
                        }
                        if(!$this->validateLastname($_POST['lastname'])){
                            $errorMessage['lastname'] = "Le Nom doit contenir entre 2 et 20 caractères sans chiffre ni de caractères spéciaux.";
                        }
                        if(!$this->validateStreetNumber($_POST['street_number'])){
                            $errorMessage["streetNumber"] = "Le numéro de rue est invalide.";
                        }
                        if(!$this->validateStreetName($_POST['street_name'])){
                            $errorMessage["streetName"] = "Le nom de la rue est invalide.";
                        }
                        if(!$this->validateZipCode($_POST['zipcode'])){
                            $errorMessage["zipcode"] = "Le code postal est invalide.";
                        }
                        if(!$this->validateCity($_POST['city'])){
                            $errorMessage["city"] = "La ville est invalide.";
                        }
                        if(!empty($errorMessage)){
                            $response = ['errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit();
                        }else{
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
                                    $response = ['success' => true];
                                    echo json_encode($response);
                                    exit();
                                }else{
                                    $errorMessage['request'] ="Une erreur s'est produite.";
                                    $response = ['errorMessage' => $errorMessage];
                                    echo json_encode($response);
                                    exit();
                                }
                            }else{
                                echo json_encode('ok');
                            }
                        }
                    }else{
                        $errorMessage['blank'] = "Tous les champs doivent être complétés";
                        $response = ['errorMessage' => $errorMessage];
                        echo json_encode($response);
                        exit();
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
                         $errorMessage = [];
                        if(!$this->validateFirstname($_POST['firstname'])){
                            $errorMessage['firstname'] = "Le prénom doit contenir entre 3 et 25 caractères sans chiffre.";
                        }
                        if(!$this->validateLastname($_POST['lastname'])){
                            $errorMessage['lastname'] = "Le Nom doit contenir entre 2 et 20 caractères sans chiffre.";
                        }
                        if(!$this->validateStreetNumber($_POST['street_number'])){
                            $errorMessage["streetNumber"] = "Le numéro de rue est invalide";
                        }
                        if(!$this->validateStreetName($_POST['street_name'])){
                            $errorMessage["streetName"] = "Le nom de la rue est invalide";
                        }
                        if(!$this->validateZipCode($_POST['zipcode'])){
                            $errorMessage["zipcode"] = "Le code postal est invalide";
                        }
                        if(!$this->validateCity($_POST['city'])){
                            $errorMessage["city"] = "La ville est invalide";
                        }
                        if(!empty($errorMessage)){
                            $response = ['errorMessage' => $errorMessage];
                            echo json_encode($response);
                            exit();
                        }else{

                            $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
                            $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
                            $streetNumber = htmlspecialchars(strip_tags($_POST['street_number']));
                            $streetName = htmlspecialchars(strip_tags($_POST['street_name']));
                            $zipcode = htmlspecialchars(strip_tags($_POST['zipcode']));
                            $city = htmlspecialchars(strip_tags($_POST['city']));
                            $addressModel = new Addresses;
                            $result = $addressModel->addAddress($firstname, $lastname, $streetNumber, $streetName, $zipcode, $city, $idUser);
                            if($result){
                                $response = ['success' => true];
                                echo json_encode($response);
                                exit();
                            }else{
                                $errorMessage['request']= "Une erreur s'est produite lors de l'ajout";
                                $response = ['errorMessage' => $errorMessage];
                                echo json_encode($response);
                                exit();
                            }
                        }
                    }else{
                        $errorMessage['blank'] = "Tous les champs doivent être complétés";
                        $response = ['errorMessage' => $errorMessage];
                        echo json_encode($response);
                        exit();
                    }
                }
            }else{
                header("location: /login");
            }
    }
}
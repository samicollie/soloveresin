<?php 

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Users;
use App\Models\Session;
use App\Controllers\Traits\Validatortrait;
use App\Models\Products;
use App\Services\EmailService;
use DateTime;

class AuthController extends Controller{
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
        if($this->isLoggedIn){
            header("location: /profile");
            exit();
        }
        $fields = $this->cleanFields($_POST);
        $errorMessage = $this->validateFields($_POST);
        $firstname = $fields['firstname'] ?? '';
        $lastname = $fields['lastname'] ?? '';
        $email = $fields['email'] ?? '';
        $password = $fields['password'] ?? '';
        $confirmationPassword = $fields['confirmation-password'] ?? '';
        if(empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($confirmationPassword)){
            $errorMessage['blank'] = "Tous les champs doivent être complétés.";
        }
        if(!empty($errorMessage)){
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $userModel = new Users;
        if($userModel->isExistingAccount($email)){
            $errorMessage['email'] = "Cet email est déjà utilisé";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        if($userModel->newUser($firstname, $lastname, $email, $password)){
            EmailService::specificSendEmail("validation", $email,$userModel->getUserByEmail($email)->id_user);
            $this->sendJSONResponse(['success' => true]);
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
            if($user->is_verified){
                header("location: /login");
                exit();
            }
            $date = new DateTime();
            if($date > new DateTime($user->created_at)){
                $errorMessage = "Désolé le lien est invalide.";
                $this->render('user/verifiedAccount', 'Validation du compte', ['errorMessage' => $errorMessage]);
            }else{
                $userModel->makeVerified($user->id_user);
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
        if($this->isLoggedIn()){
            header("location: /profile");
            exit();
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->render('user/generateLink', 'Générer un lien d\'activation de compte');
        }elseif($_SERVER['REQUEST_METHOD'] === 'POST'){

            $fields = $this->cleanFields($_POST);
            $email = $fields['email'] ?? '';
            if(empty($email)){
                $errorMessage['blank'] = "Le champ ne peut pas être vide";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            $userModel = new Users;
            $user = $userModel->getUserByEmail($email);
            if(!$user){
                $errorMessage['email'] = "Cet email n'existe pas";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            if($user->is_verified){
                $errorMessage['email'] = "Cet email est déjà vérifié.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            if(!$user->count_link < 5){
                $errorMessage['email'] = "Vous avez déjà utilisez toutes vos chances pour valider votre compte.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            if(!EmailService::specificSendEmail("validation", $email,$user->id_user)){
                $errorMessage['email'] = "Une erreur s'est produite lors de l'envoi de l'email.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            $this->sendJSONResponse(['success' => true]);
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
        if($this->isLoggedIn){
            header("location: /profile");
            exit();
        }
        $fields = $this->cleanFields($_POST);
        $email = $fields['email'] ?? '';
        $password = $fields['password'] ?? '';
        if(empty($email) || empty($password)){
            $errorMessage['blank'] = "Tous les champs doivent être complétés";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $this->IsUserExist($email);
        $this->isUserVerified($email);
        $this->verifyPassword($email, $password);
        $this->updateUserSession($email);
        $this->handleUserCart($email);
        $this->sendJSONResponse(['success' => true]);
    }

    /**
     * verify if and user exist with an email
     *
     * @param string $email
     * @return void
     */
    private function IsUserExist(string $email){
        $userModel = new Users;
        $user = $userModel->getUserByEmail($email);
        if(!$user){
            $errorMessage['email'] = "Email ou mot de passe incorrect";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        
    }

    /**
     * verify if the user account is verified
     *
     * @param string $email
     * @return boolean
     */
    private function isUserVerified(string $email){
        $userModel = new Users;
        $user = $userModel->getUserByEmail($email);
        if(!$user->is_verified){
            $errorMessage['email'] = "Veuillez valider votre email avant de vous connecter";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
    }

    /**
     * verify if the password sens in formular match with the database password
     *
     * @param string $email
     * @param string $password
     * @return void
     */
    private function verifyPassword(string $email, string $password){
        $userModel = new Users;
        $user = $userModel->getUserByEmail($email);
        if(!password_verify($password, $user->password)){
            $errorMessage['email'] = "Email ou mot de passe incorrect";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
    }

    /**
     * update the session in database in adding id_user to the session
     *
     * @param string $email
     * @return void
     */
    private function updateUserSession(string $email){
        $userModel = new Users;
        $user = $userModel->getUserByEmail($email);
        $idUser = $user->id_user;
        $_SESSION['id_user'] = $idUser;
        $sessionModel = new Session;
        $cartModel = new Cart;
        $idCart = $cartModel->getIdCartFromIdUser($idUser);
        $sessionModel->AddIdUserSession($_SESSION['session_id'],$idUser);
    }

    /**
     * update the car on database from the Session cart
     *
     * @param string $email
     * @return void
     */
    private function handleUserCart(string $email){
        $cartModel = new Cart;
        $userModel = new Users;
        $productModel = new Products;
        $user = $userModel->getUserByEmail($email);
        $idCart = $cartModel->getIdCartFromIdUser($user->id_user);
        if(isset($_SESSION['cart'])){
            if(isset($_COOKIE['cart'])){
                setcookie('cart','', time() - 3600, "/");
            }
            $cart = $_SESSION['cart'];
            foreach($cart as $item){
                $productId = $item['id'];
                $quantity = $item['quantity'];
                if($cartModel->isExistingProduct($idCart, $productId)){
                    $cartModel->updateQuantity($idCart, $productId, $quantity);
                }else{
                    $cartModel->fromSessionToCart($idCart, $productId, $quantity);
                }
            }
            $cartModel->updateProductsInCartAboutMaxQuantity($idCart);
        }else{
            $cartModel->updateProductsInCartAboutMaxQuantity($idCart);
        }
    }

    /**
     * logout an user
     *
     * @return void
     */
    public function logoutUser(){
        $this->isAccessAllowed();
        $sessionModel = new Session;
        $sessionModel->deleteSession($_SESSION['id_user']);
        session_unset();
        session_destroy();
        header("location: /login");
    }

    /**
     * displays formular to reset password, send email after submit
     *
     * @return void
     */
    public function resetPassword(){
        if($this->isLoggedIn()){
            header("location: /profile");
            exit();
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->render("user/resetPassword", "Réinitialisation du mot de passe");
        }else{
            $fields = $this->cleanFields($_POST);
            $email = $fields['email'] ?? '';
            if(empty($email)){
                $errorMessage['email'] = "Tous les champs doivent être complétés";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            $userModel = new Users;
            $user = $userModel->getUserByEmail($email);
            if(!$user){
                $errorMessage['email'] = "Cet email n'existe pas";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            if(!$user->is_verified){
                $errorMessage['email'] = "Cet email n'est pas validé.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            if(!EmailService::specificSendEmail("reset", $email, $user->id_user)){
                $errorMessage['email'] = "Une erreur s'est produite lors de l'envoi de l'email.";
                $this->sendJSONResponse(['errorMessage' => $errorMessage]);
            }
            $this->sendJSONResponse(['success' => true, 'message' => 'Le message a été envoyé, vérifiez vos mails.']);
            
        }
    }

    /**
     * validate the token to reset password
     *
     * @param array $params
     * @return void
     */
    public function validateResetPassword(array $params){
        $resetToken = $params[0];
        $userModel = new Users;
        $user = $userModel->getUserWithResetToken($resetToken);
        if($user){
            $date = new DateTime();
            if($date > new DateTime($user->created_at)){
                $errorMessage = "Désolé le lien est invalide. Veuillez en générer un nouveau.";
                $this->render('user/resetPassword', 'Réinitialisation du mot de passe', ['errorMessage' => $errorMessage]);
            }else{
                $this->render('user/newPassword', 'Réinitialisation du mot de passe', ['resetToken' => $resetToken]);
            }
        }else{
            $errorMessage = "Désolé le lien est invalide. Veuillez en générer un nouveau.";
            $this->render('user/resetPassword', 'Réinitialisation du mot de passe', ['errorMessage' => $errorMessage]);
        }
    }

    /**
     * update the password of an user
     *
     * @return void
     */
    public function newPassword(){
        $fields = $this->cleanFields($_POST);
        $errorMessage = $this->validateFields($fields);
        $password = $fields['password'] ?? '';
        $resetToken = $fields['token'] ?? '';
        $confirmationPassword = $fields['confirmation-password'] ?? '';
        if(empty($password) || empty($confirmationPassword)){
            $errorMessage['blank'] = "Tous les champs doivent être complétés."; 
        }
        if(!empty($errorMessage)){
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $userModel = new Users;
        if(!$userModel->updatePassWordWithtoken($password,$resetToken)){
            $errorMessage['request'] = "Une erreur s'est produite lors de la mise à jour du mot de passe.";
            $this->sendJSONResponse(['errorMessage' => $errorMessage]);
        }
        $this->sendJSONResponse(['success' => true, 'message' => "Le mot de passe a été modifié."]);
    }
}
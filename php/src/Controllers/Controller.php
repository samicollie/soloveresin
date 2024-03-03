<?php 

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Users;
use App\Models\Session;

abstract class Controller
{
    protected $isLoggedIn;
    protected $userRole;

    public function __construct()
    {
        $this->userRole = 'none';
        $this->isLoggedIn = $this->isLoggedIn();
    }

    /**
     * render a view with some data
     *
     * @param string $filename
     * @param string $pageTitle
     * @param array $data
     * @return void
     */
    public function render(string $filename,  string $pageTitle, array $data = [],)
    {
        // extract the data
        extract($data);

        //get the number of product in the cart 
        $productCounter = 0;
        if(isset($_COOKIE['cart'])) {
            $cartCounter = json_decode($_COOKIE['cart'], true);
            foreach($cartCounter as $item) {
                $productCounter += $item['quantity'];
            }
        }

        $idCart = $_COOKIE['cartId'] ?? '';
        $cartSignature = $_COOKIE['cartSignature'] ?? '';
        if($idCart && $cartSignature){
            $hash = hash_hmac('sha256', $idCart, 'Mot de Passe de Signature');
            $match = hash_equals($cartSignature, $hash);
            if($match){
                $cartModel = new Cart;
                $productCounter = $cartModel->getCartNumberProduct($idCart);
            }
        }

        //we start the buffer
        ob_start();
        //create path to the view
        require_once ROOT. '/src/Views/'. $filename . '.php';

        $content = ob_get_clean();
        $title = $pageTitle;
        require_once ROOT.'/src/Views/default.php';
    }

    /**
     * render a part of a view
     *
     * @param string $filename
     * @param array $data
     * @return void
     */
    public function renderPartial(string $filename,array $data = []):void
    {
        extract($data);
        require_once ROOT . '/src/Views/' . $filename . '.php';
    }

    /**
     * control if a user is logged in.
     *
     * @return boolean
     */
    public function isLoggedIn(){
        $signature = $_COOKIE['signature'] ?? '' ;
        $session = $_COOKIE['session'] ?? '';
        if($session && $signature){
            $hash = hash_hmac('sha256', $session, 'Mot de Passe de Signature');
            $match = hash_equals($signature, $hash);
            if($match){
                $sessionModel = new Session;
                $activeSession = $sessionModel->getSession($session);
                if($activeSession){
                    $userModel = new Users;
                    $idUser = $userModel->getUserIdBySessionId($session);
                    $this->userRole = $this->getUserRole($idUser);
                    return true;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
    * function who return the user role if it exists
    *
    * @param integer $idUser
    * @return string
    */
    protected function getUserRole(int $idUser): string
    {
        $userModel = new Users;
        $user = $userModel->getUserById($idUser)[0] ?? '';
        if($user){
            return $user->role;
        }else{
            return 'none';
        }
    }
}
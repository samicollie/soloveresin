<?php 

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Users;

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
    protected function render(string $filename,  string $pageTitle, array $data = [],)
    {
        // extract the data
        extract($data);

        // get number products in the cart
        $productCounter = $this->getNumberProductsInCart();

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
    protected function renderPartial(string $filename,array $data = []):void
    {
        extract($data);
        require_once ROOT . '/src/Views/' . $filename . '.php';
    }

    /**
     * return the number of products in the cart
     *
     * @return integer
     */
    protected function getNumberProductsInCart():int
    {
        $productCounter = 0;
        if($this->isLoggedIn()){
            $idUser = $_SESSION['id_user'];
            $cartModel = new Cart;
            $idCart = $cartModel->getIdCartFromIdUser($idUser);
            $productCounter = $cartModel->getCartNumberProduct($idCart);
            return $productCounter;
        }
        if(isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            foreach($cart as $item) {
                $productCounter += $item['quantity'];
            }
        }
        return $productCounter;
    }

    /**
     * control if a user is logged in.
     *
     * @return boolean
     */
    protected function isLoggedIn(){
        
        if(!isset($_SESSION['id_user'])){
            return false;
        }
        $idUser = $_SESSION['id_user'];
        $this->userRole = $this->getUserRole($idUser);
        return true;
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
        $user = $userModel->getUserById($idUser) ?? '';
        if($user){
            return $user->role;
        }else{
            return 'none';
        }
    }

    /**
     * send a json response
     *
     * @param array $data
     * @return void
     */
    protected function sendJSONResponse(array $data){
        echo json_encode($data);
        exit();
    }

    /**
     * verify if acces is allowed to the user
     *
     * @return boolean
     */
    protected function isAccessAllowed(){
        if(!$this->isLoggedIn){
            header("location: /login");
            exit();
        }
    }

    /**
     * verify if user is allowed to access admin page
     *
     * @return boolean
     */
    protected function isAuthorizedForAdminPage()
    {
        if(!$this->isLoggedIn){
            header("location: /login");
            exit();
        }
        if($this->userRole !== '["ROLE_ADMIN"]'){
            header("location: /profile");
            exit();
        }
    }
}
<?php 

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Products;
use App\Models\Session;

class CartController extends Controller
{
    /**
     * display the cart of an user
     *
     * @return void
     */
    public function index()
    {
        $cart = [];
        $idCart = $_COOKIE['cartId'] ?? '';
        $cartSignature = $_COOKIE['cartSignature'] ?? '';
        if($idCart && $cartSignature){
            $hash = hash_hmac('sha256', $idCart, 'Mot de Passe de Signature');
            $match = hash_equals($cartSignature, $hash);
            if($match){
                $cartModel = new Cart;
                $cartProductsId = $cartModel->getProductsIdFromCart($idCart);
                $productModel = new Products;
                foreach($cartProductsId as $cartItem){
                    $product = $productModel->getOneProduct($cartItem->id_product)[0];
                    $cart[] = [$product, $cartItem->quantity];
                }
            }
        }else{
            if(isset($_COOKIE['cart'])){
                $cartTemp = json_decode($_COOKIE['cart'], true);
                $cart = [];
                $productModel = new Products;
                foreach($cartTemp as $item){
                    $product = $productModel->getOneProduct($item['id'])[0];
                    $cart[] = [$product, $item['quantity']];
                }
            }
        }

        $this->render("cart/index", "Mon Panier", ["cart" => $cart]);
    }

    /**
     * add a product in a cart in bdd if user is logged in else in a cookie
     *
     * @return void
     */
    public function addCartProduct()
    {
        if(isset($_POST['product_id'])){
            $productId = $_POST['product_id'];
        }
        if(isset($_POST['current_url'])){
            $currentUrl = $_POST['current_url'];
        }
        $cart = [];
        if($this->isLoggedIn){
            $sessionModel = new Session;
            $session = $sessionModel->getSession($_COOKIE['session'])[0];
            $cartModel = new Cart;
            $cartModel->addProductInCart($productId, $session->id_user);
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
        }else{
        //get the cart from cookie or create one
            if(isset($_COOKIE['cart'])){
                $cart = json_decode($_COOKIE['cart'], true);
                //verify if the product is in the cart
                $productIndex = -1;
                foreach($cart as $index => $item){
                    if($item['id'] == $productId){
                        $productIndex = $index;
                        break;
                    }
                }

                if($productIndex !== -1){
                    $cart[$productIndex]['quantity'] += 1 ;
                }else{
                    $cart[]= [
                        'id' => intval($productId),
                        'quantity' => 1
                    ];
                }
                $cartData = json_encode($cart);
                setcookie('cart', $cartData, time()+ 1209600, '/');
                $productCounter = 0;
                foreach($cart as $item) {
                    $productCounter += $item['quantity'];
                }
            }else{
                $cart[] = [
                    'id' => intval($productId),
                    'quantity' => 1
                ];
                $cartData = json_encode($cart);
                setcookie('cart', $cartData, time() +  1209600, '/');
                $productCounter = 1;
            }
        }
        $this->renderPartial('cart/updateNumberProductsCart', ['productCounter' => $productCounter, 'currentUrl' => $currentUrl]);
    }

    /**
     * delete a cookie in bdd if user is logged in else in a cookie
     *
     * @return void
     */
    public function deleteCartProduct()
    {

        if(isset($_POST['product_id'])){
            $productId = $_POST['product_id'];
        }
        if($this->isLoggedIn){
            $sessionModel = new Session;
            $session = $sessionModel->getSession($_COOKIE['session'])[0];
            $cartModel = new Cart;
            $idCart = $cartModel->getIdCart($session->id_user);
            $cartModel->deleteProductInCart($idCart ,$productId);
        }else{
            $cart = json_decode($_COOKIE['cart'], true);
            foreach($cart as $index => $item){
                if($item['id'] == $productId){
                    array_splice($cart, $index, 1);
                }
            }
            $cartData = json_encode($cart);
            setcookie('cart', $cartData,  time()+ 1209600, '/');
        }
        header('location: /cart');
    }
}
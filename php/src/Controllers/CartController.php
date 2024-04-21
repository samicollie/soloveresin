<?php 

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Products;

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
        if($this->isLoggedIn()){
            $cartModel = new Cart;
            $idUser = $_SESSION['id_user'];
            $idCart = $cartModel->getIdCartFromIdUser($idUser);
            $cartProductsId = $cartModel->getProductsIdFromCart($idCart);
            $productModel = new Products;
            foreach($cartProductsId as $cartItem){
                $product = $productModel->getOneProduct($cartItem->id_product);
                $cart[] = [$product, $cartItem->quantity];
            }
        }else{
            if(isset($_SESSION['cart'])){
                $cartTemp = $_SESSION['cart'];
                $productModel = new Products;
                foreach($cartTemp as $item){
                    $product = $productModel->getOneProduct($item['id']);
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
        if(!isset($_POST['product_id'])){
            header("location: /store");
            exit();
        }
        $productId = htmlspecialchars(strip_tags($_POST['product_id']));
        $cart = [];
        if($this->isLoggedIn){
            $idUser = $_SESSION['id_user'];
            $cartModel = new Cart;
            $idCart = $cartModel->getIdCartFromIdUser($idUser);
            $cartModel->addProductInCart($productId, $idUser);
            $productCounter = $cartModel->getCartNumberProduct($idCart);
        }else{
        //get the cart from cookie or create one
            if(isset($_SESSION['cart'])){
                $cart = $_SESSION['cart'];
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
                $_SESSION['cart'] = $cart;
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
                $_SESSION['cart'] = $cart;
                $cartData = json_encode($cart);
                setcookie('cart', $cartData, time() +  1209600, '/');
                $productCounter = 1;
            }
        }
        $this->renderPartial('cart/updateNumberProductsCart', ['productCounter' => $productCounter]);
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
            $idUser = $_SESSION['id_user'];
            $cartModel = new Cart;
            $idCart = $cartModel->getIdCartFromIdUser($idUser);
            $cartModel->deleteProductInCart($idCart ,$productId);
        }else{
            $cart = $_SESSION['cart'];
            foreach($cart as $index => $item){
                if($item['id'] == $productId){
                    array_splice($cart, $index, 1);
                }
            }
            $_SESSION['cart'] = $cart;
            $cartData = json_encode($cart);
            setcookie('cart', $cartData,  time()+ 1209600, '/');
        }
        header('location: /cart');
    }
}
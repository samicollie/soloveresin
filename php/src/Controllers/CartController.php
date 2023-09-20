<?php 

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Products;

class CartController extends Controller
{
    public function index()
    {
        $cart = [];
        if(isset($_SESSION['id_user'])){
            $id = $_SESSION['id_user'];
            $cartModel = new Cart;
            $cart = $cartModel->getUserCart($id);
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

    public function addCartProduct()
    {
        if($_POST['product_id']){
            $id = $_POST['product_id'];
        }
        if($_POST['current_url']){
            $currentUrl = $_POST['current_url'];
        }

        //get the cart from cookie or create one empty
        $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

        //verify if the product is in the cart
        $productIndex = -1;
        if($cart){
            foreach($cart as $index => $item){
                if($item['id'] == $id){
                    $productIndex = $index;
                    break;
                }
            }
        }

        if($productIndex !== -1){
            $cart[$productIndex]['quantity'] += 1 ;
        }else{
            $cart[]= [
                'id' => intval($id),
                'quantity' => 1
            ];
        }
        $cartData = json_encode($cart);
        setcookie('cart', $cartData, time()+ 1209600, '/');

        header('location: ' . $currentUrl);

    }

    public function deleteCartProduct()
    {
        if(isset($_POST['product_id'])){
            $id_product = $_POST['product_id'];
        }
        if(isset($_SESSION['id_user'])){
            $cartDelete = new Cart;
            $cartDelete->delete($id_product);
        }else{
            $cart = json_decode($_COOKIE['cart'], true);
            foreach($cart as $index => $item){
                if($item['id'] == $id_product){
                    array_splice($cart, $index, 1);
                }
            }
            $cartData = json_encode($cart);
            setcookie('cart', $cartData,  time()+ 1209600, '/');
        }
        
        header('location: /cart');
    }
}
<?php 

namespace App\Controllers;

use App\Models\Cart;

abstract class Controller
{
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

        if(isset($_SESSION['id_user'])){
            $id = $_SESSION['id_user'];
            $cartCounterModel = new Cart;
            $productCounter = $cartCounterModel->getCartNumberProduct($id);
        }

        //we start the buffer
        ob_start();

        //create path to the view
        require_once ROOT. '/src/Views/'. $filename . '.php';

        $content = ob_get_clean();
        $title = $pageTitle;
        require_once ROOT.'/src/Views/default.php';
    }
}
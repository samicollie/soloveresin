<?php 

namespace App\Controllers;

use App\Models\Comments;
use App\Models\Products;

class ProductsController extends Controller
{
    /**
     * display the store view with all products
     *
     * @return void
     */
    public function index()
    {
        $model = new Products;
        $products = $model->getAllProducts();

        $this->render('store/index' , 'Boutique So Love Resin', ["products" => $products]);
    }

    /**
     * display the product view with only one product with his comments
     *
     * @param array $param
     * @return void
     */
    public function getProduct(array $param)
    {
        $id = intval($param[0]);
        $modelProduct = new Products;
        $product= $modelProduct->getOneProduct($id);
        $modelComment = new Comments;
        $comments = $modelComment->getComments($id);

        $this->render('store/product', 'Produit : ' . $product->product_name , ["product" => $product, "comments" => $comments] );
    }


    public function searchProducts()
    {
        if(isset($_POST['search'])){
            if(!empty($_POST['search'])){
                $search = htmlspecialchars(strip_tags(urldecode($_POST['search'])));
                $productModel = new Products;
                $products = $productModel->searchProducts($search);
                $this->renderPartial('store/searchProducts', ['products' => $products]);
            }else{
                $productModel = new Products;
                $products = $productModel->getAllProducts();
                $this->renderPartial('store/searchProducts', ['products' => $products]);
            }
        }
    }
    
}
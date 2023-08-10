<?php
namespace App\Controllers;

use App\Models\Products;

class HomeController extends Controller {

    public function index (){
        $model = new Products;

        $products = $model->findAll();

        $this->render('home/index', ['products' => $products]);

    }
}
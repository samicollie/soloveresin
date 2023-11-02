<?php
namespace App\Controllers;

use App\Models\Cart;
use App\Models\Pictures;

class HomeController extends Controller {

    /**
     * display the landing page
     *
     * @return void
     */
    public function index (){
        $model = new Pictures();
        $arrayALlPictures = $model->findAll();
        for($i=0; $i<4; $i++){
            $index = random_int(0, count($arrayALlPictures) - 1);
            $pictures[] = array_slice($arrayALlPictures, $index,1)[0];
            array_splice($arrayALlPictures, $index, 1);
        }

        $this->render('home/index', 'Accueil : So Love Resin', ['pictures' => $pictures]);

    }
}
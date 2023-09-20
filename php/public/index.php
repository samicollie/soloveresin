<?php
use App\Models\Router\Router;

require '../vendor/autoload.php';

define('ROOT', dirname(__DIR__));

$router = new Router($_SERVER['REQUEST_URI']);

$router->get('/', 'Home@index');

$router->get('/store', 'Products@index');

$router->get('/store/product/:id','Products@getProduct');

$router->get('/cart', 'Cart@index');

$router->post('/cart/add', 'Cart@addCartProduct');

$router->post('/cart/delete', 'Cart@deleteCartProduct');

$router->run();
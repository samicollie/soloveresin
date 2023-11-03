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

$router->get('/register', 'User@indexRegister');

$router->get('/login', 'User@indexLogin');

$router->post('/register', 'User@registerUser' );

$router->post('/login', 'User@loginUser');

$router->get('/profile', 'User@indexProfile');

$router->get('/logout', 'User@logoutUser');

$router->get('/profile/contact/modify/:id', 'User@modifyContact');

$router->post('/profile/contact/modify', 'User@updateContact');

$router->get('/profile/address/modify/:id', 'User@modifyAddress');

$router->post('/profile/address/modify', 'User@updateAddress');

$router->post('/profile/address/delete', 'User@deleteAddress');

$router->get('/profile/address/add', 'User@indexAddAddress');

$router->post('/profile/address/add', 'User@addAddress');

$router->run();
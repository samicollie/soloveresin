<?php
session_start();

use App\Models\Router\Router;
use App\Models\Session;

require '../vendor/autoload.php';

define('ROOT', dirname(__DIR__));

require_once ROOT . '/bootstrap.php';

if(!isset($_SESSION['session_id'])){
    $sessionId = bin2hex(random_bytes(32));
    $_SESSION['session_id'] = $sessionId;
    $signature = hash_hmac('sha256', $sessionId, $_ENV['SIGN_PASSWORD']);
    $_SESSION['signature'] = $signature;
    $sessionModel = new Session;
    $sessionModel->addNewSession($sessionId);
    
}

$router = new Router($_SERVER['REQUEST_URI']);

$router->get('/', 'Home@index');

$router->get('/store', 'Products@index');

$router->post('/store', 'Products@searchProducts');

$router->get('/store/product/:id','Products@getProduct');

$router->get('/cart', 'Cart@index');

$router->post('/cart/add', 'Cart@addCartProduct');

$router->post('/cart/delete', 'Cart@deleteCartProduct');

$router->get('/register', 'Auth@indexRegister');

$router->get('/login', 'Auth@indexLogin');

$router->post('/register', 'Auth@registerUser' );

$router->get('/register/success', 'Auth@successRegister');

$router->get('/validation/account/:resetToken', 'Auth@validateAccount');

$router->get('/generate/link', 'Auth@generateLink');

$router->post('/generate/link', 'Auth@generateLink');

$router->get('/generate/link/confirmation', 'Auth@confirmationLink');

$router->post('/login', 'Auth@loginUser');

$router->get('/session/update', 'Session@updateLastAction');

$router->get('/logout', 'Auth@logoutUser');

$router->get('/resetPassword', 'Auth@resetPassword');

$router->post('/resetPassword', 'Auth@resetPassword');

$router->get('/newPassword/:resetToken', 'Auth@validateResetPassword');

$router->post('/newPassword', 'Auth@newPassword');

$router->get('/profile', 'Account@indexProfile');

$router->get('/profile/contact/modify/:id', 'Account@modifyContact');

$router->post('/profile/contact/modify', 'Account@updateContact');

$router->get('/profile/address/modify/:id', 'Addresses@modifyAddress');

$router->post('/profile/address/modify', 'Addresses@updateAddress');

$router->post('/profile/address/delete', 'Addresses@deleteAddress');

$router->get('/profile/address/add', 'Addresses@indexAddAddress');

$router->post('/profile/address/add', 'Addresses@addAddress');

$router->get('/admin/dashboard', 'Admin@index');

$router->get('/admin/products/list', 'AdminProducts@listModifyProducts');

$router->post('/admin/products/list', 'AdminProducts@searchProducts');

$router->get('/admin/products/modify/:id', 'AdminProducts@modifyProduct');

$router->get('/admin/picture/delete/:id_picture/:id_product', 'AdminProducts@deletePicture');

$router->post('/admin/products/modify', 'AdminProducts@updateProduct');

$router->get('/admin/products/add', 'AdminProducts@indexAddProduct');

$router->post('/admin/products/add', 'AdminProducts@addProduct');

$router->post('/admin/product/delete', 'AdminProducts@deleteProduct');

$router->get('/admin/categories/list', 'AdminCategories@listModifyCategories');

$router->get('/admin/category/add', 'AdminCategories@indexAddCategory');

$router->post('/admin/category/add', 'AdminCategories@addCategory');

$router->get('/admin/category/modify/:id', 'AdminCategories@modifyCategory');

$router->post('/admin/category/modify', 'AdminCategories@updateCategory');

$router->post('/admin/category/delete', 'AdminCategories@deleteCategory');

$router->get('/contact', 'Home@contactFormular');

$router->post('/contact', 'Home@contactUs');

$router->run();
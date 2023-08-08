<?php
use App\Models\Router\Router;

require '../vendor/autoload.php';


$router = new Router($_SERVER['REQUEST_URI']);

$router->get('/', 'Home@index');

$router->get('/bonjour','Home@index');

$router->run();
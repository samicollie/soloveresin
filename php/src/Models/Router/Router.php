<?php 

namespace App\Models\Router;

class Router{

    private string $url;
    private array $routes = [];

    public function __construct($url)
    {
        $this->url = trim($url, '/');
    }

    
    public function get(string $path, string $callable)
    {
        $route = new Route($path, $callable);
        $this->routes['GET'][] = $route;
    }


    public function post(string $path, string $callable)
    {
        $route = new Route($path, $callable);
        $this->routes['POST'][] = $route;
    }

    public function run(){
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
            if($route->match($this->url)){
                return $route->call();
            }
        }
        throw new RouterException('No matching routes');
    }
}
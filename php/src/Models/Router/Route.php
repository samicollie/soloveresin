<?php

namespace App\Models\Router;
use App\Controller;

class Route {

    private $path;
    private $callable;
    private $matches;

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function match( string $url)
    {
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if(!preg_match($regex, $url, $matches)){
            return false;
        }
        $this->matches = array_shift($matches);
        return true;
    }

    public function call()
    {
        $params = explode('@', $this->callable);
        $controller = "App\\Controllers\\" . $params[0] . "Controller";
        $controller = new $controller();
        $method = $params[1];

        isset($this->matches) ? $controller->$method($this->matches) : $controller->$method();
    }
}
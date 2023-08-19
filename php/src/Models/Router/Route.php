<?php

namespace App\Models\Router;

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
        preg_match($regex, $url, $matches);
        
        if(!preg_match($regex, $url, $matches)){
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
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
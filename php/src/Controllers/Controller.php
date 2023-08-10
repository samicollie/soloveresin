<?php 

namespace App\Controllers;

abstract class Controller
{
    public function render(string $filename, array $data = [])
    {
        // extract the data
        extract($data);

        //create path to the view
        require_once ROOT. '/src/Views/'. $filename . '.php';
    }
}
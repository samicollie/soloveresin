<?php 

namespace App\Controllers;

abstract class Controller
{
    public function render(string $filename, array $data = [])
    {
        // extract the data
        extract($data);

        //we start the buffer
        ob_start();

        //create path to the view
        require_once ROOT. '/src/Views/'. $filename . '.php';

        $content = ob_get_clean();

        require_once ROOT.'/src/Views/default.php';
    }
}
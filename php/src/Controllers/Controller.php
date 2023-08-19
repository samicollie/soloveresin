<?php 

namespace App\Controllers;

abstract class Controller
{
    public function render(string $filename,  string $pageTitle, array $data = [],)
    {
        // extract the data
        extract($data);

        //we start the buffer
        ob_start();

        //create path to the view
        require_once ROOT. '/src/Views/'. $filename . '.php';

        $content = ob_get_clean();
        $title = $pageTitle;
        require_once ROOT.'/src/Views/default.php';
    }
}
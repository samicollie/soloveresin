<?php
namespace App\Controllers;

use App\Models\Session;

class SessionController extends Controller {

    public function updateLastAction(){
        if($this->isLoggedIn()){
            $sessionModel = new Session;
            $sessionModel->updateSession($_SESSION['session_id']);
        }
    }
}
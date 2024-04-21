<?php 

namespace App\Controllers;

use App\Controllers\Traits\Validatortrait;

class AdminController extends Controller{
    use Validatortrait;

    /**
     * display the admin dashboard
     *
     * @return void
     */
    public function index()
    {
        $this->isAuthorizedForAdminPage();
        $this->render('admin/dashboard', 'Tableau de bord administrateur');

    }
}
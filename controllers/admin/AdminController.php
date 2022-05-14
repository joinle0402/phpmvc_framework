<?php
namespace application\controllers\admin;

use application\core\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return $this->view('pages.admin.index');
    }

    public function accounts()
    {
        return $this->view('pages.admin.accounts.index');
    }

}

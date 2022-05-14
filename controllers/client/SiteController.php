<?php
namespace application\controllers\client;

use application\core\Controller;
use application\models\AccountModel;

class SiteController extends Controller
{
    private AccountModel $accountModel;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
    }

    public function index()
    {
        $this->accountModel->load([ 'username' => $_COOKIE['rememberUsername'] ]);
        $account = $this->accountModel->findAccountByUsername()[0];

        $parameters = [];
        $parameters['role'] = $account['role'];


        return $this->view('pages.client.index', $parameters );
    }

}

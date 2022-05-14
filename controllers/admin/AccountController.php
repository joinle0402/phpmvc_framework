<?php
namespace application\controllers\admin;

use application\core\Controller;
use application\core\Request;
use application\models\AccountModel;
use application\core\Response;
use application\core\Session;

class AccountController extends Controller
{
    private AccountModel $accountModel;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
    }

    public function index()
    {
        $parameters = [];
        $parameters['accounts'] = $this->accountModel->all();

        return $this->view('pages.admin.accounts.index', $parameters);
    }
    public function addAccountForm()
    {
        $parameters = [];
        $parameters['errors'] = Session::flash('errors');
        $parameters['account'] = Session::flash('account');

        return $this->view('pages.admin.accounts.addAccountForm', $parameters);
    }

    public function updateAccountForm(Request $request, Response $response)
    {
        $accountId = intval($request->getRouteParameters()['accountId']);
        $parameters = [];
        $parameters['accountId'] = $accountId;
        $parameters['account'] = $this->accountModel->findById($accountId);
        $parameters['errors'] = Session::flash('errors');

        return $this->view('pages.admin.accounts.updateAccountForm', $parameters);
    }

    public function addAccount(Request $request, Response $response)
    {
        $request->setRules([
            "firstName" => "required|min:3|max:40",
            "lastName" => "required|min:3|max:40",
            "email" => 'required|email|min:6|unique:accounts',
            "username" => 'required|min:5|max:40|unique:accounts',
            "password" => 'required|min:5|max:40',
            "confirmPassword" => 'required|min:5|max:40|match:password'
        ]);

        if ($request->validate())
        {
            $accountModel = new AccountModel($request->getDataRequest());
            $accountModel->register();

            return $response->redirect('/admin/accounts');
        }

        Session::flash('errors', $request->getFirstErrors());
        Session::flash('account', $request->getDataRequest());

        return $response->redirect('/admin/accounts/addAccountForm');
    }

    public function updateAccount(Request $request, Response $response)
    {
        $request->setRules([
            "firstName" => "required|min:3|max:40",
            "lastName" => "required|min:3|max:40",
            "email" => 'required|email|min:6',
            "username" => 'required|min:5|max:40',
            "password" => 'required|min:5|max:40',
            "confirmPassword" => 'required|min:5|max:40|match:password'
        ]);

        if ($request->validate())
        {
            $this->accountModel->load($request->getDataRequest());
            $this->accountModel->updateAccount();

            return $response->redirect('/admin/accounts');
        }

        Session::flash('errors', $request->getFirstErrors());
        Session::flash('account', $request->getDataRequest());

        return $response->redirect('/admin/accounts/update/'.$request->getDataRequest()['id']);
    }

    public function deleteAccount(Request $request, Response $response)
    {
        $accountId = intval($request->getRouteParameters()['accountId']);
        $this->accountModel->deleteById($accountId);
        return $response->redirect('/admin/accounts');
    }

}
